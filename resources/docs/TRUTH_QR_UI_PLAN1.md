
# TRUTH QR UI — Package Plan

A lightweight Laravel UI package that **demonstrates and exercises** the two core libraries:

- `truth-codec-php` — envelope + serializer + transport codecs
- `truth-qr-php` — publishing (splitting + enveloping + QR generation) and classifying (ingesting + assembling)

The UI lets a user **paste / upload JSON, YAML, or plain text**, generate **one or many QR codes**, and then **scan** (webcam or file) to **re‑materialize** the payload and verify round‑trips.

---

## Goals

1. **Visual demo** of the round‑trip: data → QR(s) → data.
2. **Feature coverage**: choose serializer (`json|yaml`), transport (`none|base64url|base64url+deflate|base64url+gzip`), envelope (`line|url`), and QR writer (`bacon|endroid`).
3. **Ergonomics**: copy/download QRs, shareable deep links (options in query).
4. **Reliability**: show progress while scanning; tolerate duplicates; report missing indices.
5. **Host‑friendly**: mountable route group with optional middleware (auth, throttle).

---

## Tech & Dependencies

- **Laravel 10/11** app (package-friendly).
- **Livewire v3** + **Alpine.js** for reactive UI.
- **QR scanning**: `@zxing/library` (browser camera), fallback file-drop.
- **Styling**: TailwindCSS (simple, utility‑first).

Consumes (as Composer deps):
```bash
composer require lbhurtado/truth-codec-php lbhurtado/truth-qr-php livewire/livewire
npm i @zxing/browser @zxing/library
```

---

## Package Skeleton

```
packages/truth-qr-ui/
  src/
    TruthQrUiServiceProvider.php
    Http/Controllers/UiController.php
    Livewire/
      PublishPanel.php
      ScannerPanel.php
      RoundTripPanel.php
    Support/
      PayloadExamples.php
  resources/views/
    layout.blade.php
    publish.blade.php
    scan.blade.php
    roundtrip.blade.php
  routes/truth-qr-ui.php
  config/truth-qr-ui.php
  README.md
```

---

## Service Provider

- Publishes config `truth-qr-ui.php`.
- Exposes a **route macro** `Route::truthQrUi($options = [])` to mount UI under a chosen prefix/middleware.

**Example macro** (in `TruthQrUiServiceProvider`):
```php
use Illuminate\Support\Facades\Route;
use TruthQr\Ui\Http\Controllers\UiController;

Route::macro('truthQrUi', function (array $opts = []) {
    $prefix = $opts['prefix'] ?? 'truth-ui';
    $mw     = $opts['middleware'] ?? ['web'];
    Route::group(compact('prefix', 'mw') + ['middleware' => $mw], function () {
        Route::get('/',        [UiController::class, 'home'])->name('truth-ui.home');
        Route::get('/publish', [UiController::class, 'publish'])->name('truth-ui.publish');
        Route::get('/scan',    [UiController::class, 'scan'])->name('truth-ui.scan');
        Route::get('/roundtrip',[UiController::class, 'roundtrip'])->name('truth-ui.roundtrip');
    });
});
```

**Host app usage:**
```php
// routes/web.php (or routes/ui.php)
Route::truthQrUi([
  'prefix'     => 'qr',
  'middleware' => ['web', 'throttle:60,1'], // optional
]);
```

---

## Config (`config/truth-qr-ui.php`)

```php
return [
    // Defaults forwarded to truth-qr-php publisher / classify
    'serializer' => env('TRUTH_SERIALIZER', 'auto'), // json|yaml|auto
    'transport'  => env('TRUTH_TRANSPORT',  'base64url+deflate'),
    'envelope'   => env('TRUTH_ENVELOPE',   'line'), // line|url
    'writer'     => [
        'driver' => env('TRUTH_QR_WRITER', 'bacon'), // bacon|endroid|null
        'format' => env('TRUTH_QR_FORMAT', 'svg'),   // svg|png|eps(bacon)
        'bacon'  => ['size' => 512, 'margin' => 16],
        'endroid'=> ['size' => 512, 'margin' => 16],
    ],
    'publish' => [
        'strategy' => 'size', // 'count' | 'size'
        'count'    => 3,
        'size'     => 120,    // chars per part (post-transport)
    ],
];
```

---

## UI Pages

### 1) **Publish** (`/publish`)

Left pane: input editor (tabs: JSON | YAML | Text).  
Right pane: options + output.

**Options:**
- Serializer (`auto|json|yaml`)
- Transport (`none|base64url|base64url+deflate|base64url+gzip`)
- Envelope (`line|url`)
- Publish strategy: `count:N` or `size:K`
- Writer & format (`bacon|endroid` + `svg|png`) and size/margin

**Action:**
- Calls `TruthQrPublisherFactory::publish()` to produce **lines/urls**.
- Optionally `publishQrImages()` to get **SVG/PNG** strings (display & download).

**Output:**
- Grid of QR images with the **exact text** beneath each image.
- Download buttons: **ZIP** (images + manifest), **CSV** (lines), **JSON manifest**.

### 2) **Scan** (`/scan`)

- Webcam panel using **@zxing/browser**.
- As each scan succeeds, push the **full text** into a Livewire list.
- **ClassifySession** ingests lines and reports:
    - total, received, missing[], duplicates handled
    - when complete: **assemble** and show decoded payload (pretty JSON).

Also support:
- **Upload images** (drag & drop) → run ZXing decode on blobs.
- **Paste lines** → one per line text box.

### 3) **Round-trip** (`/roundtrip`)

- Single page that includes **Publish** on top and **Scan** below.
- Button _“Send to Scanner”_ preloads the generated lines into the scan panel
  (simulate mobile scan flow for demos).

---

## Livewire Components (v3)

### `PublishPanel`

Props: none (reads config defaults).  
State: `payload`, `serializer`, `transport`, `envelope`, `strategy`, `count`, `size`, `writer`, `format`, `qrSize`, `qrMargin`.  
Actions:
- `publish()` → uses `TruthQrPublisherFactory` (from container) to produce lines and images.
- `download(type)` → builds ZIP/CSV/JSON response via controller route.

### `ScannerPanel`

State: `lines = []`, `status = {total, received, missing[]}`, `decoded`.  
Actions:
- `addLine($text)` → feeds `ClassifySession`.
- `assemble()` when complete.
- `reset()` clears session.
- Client JS emits `qr-scanned` events (webcam/file) → Livewire `addLine`.

### `RoundTripPanel`

Composes both panels; wires “publish → scanner preload”.

---

## Controllers

`UiController` only returns views; Livewire handles actions.  
Add 1–2 helper routes for **downloads**:
- `/publish/manifest.json`
- `/publish/images.zip`
- `/publish/lines.csv`

These read the last Livewire-persisted data (lightweight cache in session) or rebuild on demand.

---

## How Publishing Works (delegated)

```php
$factory   = app(\TruthQr\Publishing\TruthQrPublisherFactory::class);
$payload   = /* array from JSON/YAML/text */;
$code      = $payload['code'] ?? Str::upper(Str::random(8));

// URLs or line strings
$lines     = $factory->publish($payload, $code, [
  'by'    => 'size', // or 'count'
  'size'  => 120,
  'count' => 3,
]);

// Optional images
$writer   = app(\TruthQr\Contracts\TruthQrWriter::class); // respects config writer
$images   = $factory->publishQrImages($payload, $code, $writer, [
  'by' => 'size', 'size' => 120
]);
```

---

## How Scanning Works (delegated)

```php
$assembler = app(\TruthQr\Assembly\TruthAssembler::class);
$classify  = new \TruthQr\Classify\Classify($assembler);
$session   = $classify->newSession();

foreach ($scannedLines as $line) {
    $session->addLine($line); // ignores dupes, validates envelope
}

$status = $session->status(); // code, total, received, missing[]

if ($session->isComplete()) {
    $decoded = $session->assemble(); // array payload
}
```

---

## Persistence (optional)

The UI is demo‑oriented; by default it keeps everything in memory. You can enable persistence via the underlying libraries if you bind a real `TruthStore` (e.g. Redis) in the host app. The UI will reflect completion and artifacts via the assembler API you already exposed.

---

## Testing Strategy

- **Feature tests** for pages render & Livewire actions:
    - Publish produces deterministic number of lines for given strategy.
    - Scanner reports missing when we drop a chunk; assembles when complete.
- **JS smoke**: one Dusk test per page (optional) to ensure ZXing init and basic emission works.
- **End‑to‑end**: JSON → publish (`count=3`) → feed to scanner → equals original payload.

---

## Security & Limits

- Throttle the mounted routes: `throttle:60,1` (configurable).
- Validate max payload size (e.g., 256 KB for demo).
- Sanitize YAML → array using a safe parser.
- Never eval or render untrusted HTML.

---

## Roadmap

1. MVP pages + Livewire + ZXing (camera + file decode)
2. Download artifacts (ZIP/CSV/JSON)
3. Deep‑link options via query string → hydrate UI
4. Clipboard helpers (copy line, copy data URI)
5. Worker offloading for heavy QR generation (optional)

Stretch:
- QR style theming (colors, logo, corner shapes)
- Offline PWA demo
- Signed payloads & verification badge
- Multi‑language UI

---

## Dev Setup

```bash
# in host app
composer require lbhurtado/truth-qr-ui:@dev
php artisan vendor:publish --tag=truth-qr-ui-config

# mount routes
// routes/web.php
Route::truthQrUi(['prefix' => 'qr', 'middleware' => ['web', 'throttle:60,1']]);

# frontend
npm i @zxing/browser @zxing/library
npm run dev
```

You should now be able to visit `/qr/publish`, `/qr/scan`, and `/qr/roundtrip` to interact with the UI.
