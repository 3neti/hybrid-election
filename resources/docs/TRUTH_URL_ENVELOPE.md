# TRUTH URL Envelope Ingestion Plan
**Version:** 1.0  
**Owner:** Hybrid Election — TRUTH Initiative  
**Tagline:** *Rebuild the tally. See the truth.*

> This document specifies how a printed set of QR codes can drive a **tap-to-ingest** experience via ordinary camera apps, automatically collecting chunked data (ER, BAL, CAN) and rendering the reconstructed artifact (Election Return, Ballot, or Canvass) in the browser—without requiring a native app.

---

## 1) Problem & Goal

- **Problem:** Printed artifacts (ER/BAL/CAN) often require trust in intermediaries. Users should be able to reconstruct the *exact* data from what’s printed, using their phone camera alone.
- **Goal:** Encode each chunk as a **URL** so the phone’s camera can open a web page that:
    1) **Receives** the chunk data, **stores** it server-side (with a session code),
    2) **Guides** the user to scan remaining codes, and
    3) **Materializes** the original JSON/YAML and renders a printable view (ER/BAL/CAN) once complete.

---

## 2) Envelope & URL Formats

### 2.1 Line (Human-readable) Envelope
Compatible with legacy ER lines and the new TRUTH family.

```
ER|v1|<CODE>|<i>/<N>|<payloadPart>
TRUTH|v1|<CODE>|<i>/<N>|<payloadPart>   # new family (recommended going forward)
```

- `ER` / `TRUTH` → **prefix**
- `v1` → **version**
- `<CODE>` → **session** / dataset identifier (e.g., precinct code, random token)
- `<i>/<N>` → chunk index and total
- `<payloadPart>` → chunk fragment (transport-encoded text; may be raw, base64url, or gzip+base64url)

> **Migration:** Readers must accept both `ER|v1|...` and `TRUTH|v1|...`. Writers should move to `TRUTH|v1|...` in Phase 5.

### 2.2 URL Envelope (scan-friendly)
Each QR encodes a clickable URL:

```
https://example.org/truth/ingest
  ?f=truth   # family/prefix: 'truth' or 'er' (case-insensitive)
  &v=1       # version (string/number)
  &code=XYZ  # session code
  &i=2       # index (1-based)
  &n=5       # total
  &p=...     # payloadPart (URL-safe), optional 't' below tells transport
  &t=b64u    # transport hint: raw|b64u|gz+b64u (optional but recommended)
```

**Examples**
```
https://example.org/truth/ingest?f=er&v=1&code=8H4MUMKCOZ2L&i=1&n=6&p=...&t=b64u
https://example.org/truth/ingest?f=truth&v=1&code=C3VJOY0NVVHP&i=3&n=5&p=...&t=gz+b64u
```

**Why URL-based?**
- Works with any default camera app → tap to open.
- **Stateless QR**: each chunk is independent; the server/session manages progress.

---

## 3) End-to-End User Flow

### A) “Plain Camera” Flow
1. User opens their phone camera, scans QR #1 → **/truth/ingest?…**
2. Server **records** the chunk (by `code/i/n`), **redirects** to **/truth/collect/:code** progress page.
3. Page shows **progress** (received indices, total, next missing) and a **“Scan next”** CTA.
4. For each subsequent QR scan, the same **ingest** endpoint is hit; page updates live (SSE or polling).
5. Once all chunks are received, server assembles and **redirects** to **/truth/render/:code** to display the **ER/BAL/CAN**.

### B) “In-browser Scanner” Flow
1. User goes to **/truth** and taps **Start camera** to use a built-in scanner.
2. As codes are scanned, the app calls **/truth/ingest** behind the scenes (no tab hopping).
3. Progress updates; when complete → **/truth/render/:code**.

> Both flows use the **same backend**: `/truth/ingest` + `/truth/progress/:code` + `/truth/render/:code`.

---

## 4) Backend Design (Laravel)

### 4.1 Minimal Endpoints
```php
// routes/web.php
Route::get('/truth/ingest', [TruthIngestController::class, 'ingest']);   // GET for camera URLs
Route::get('/truth/collect/{code}', [TruthCollectController::class, 'show']); // progress page
Route::get('/truth/progress/{code}', [TruthProgressController::class, 'json']); // JSON status
Route::get('/truth/render/{code}', [TruthRenderController::class, 'show']);     // assembled view
```

### 4.2 Storage Model
Use **Redis** keyed by `truth:<code>`:
```txt
truth:<code>:meta      => { family, version, total, transport, format?, created_at }
truth:<code>:chunk:<i> => payloadPart (raw/b64u/gz+b64u per transport)
truth:<code>:status    => { received[], missing[], complete:bool }
truth:<code>:artifact  => full serialized blob (cached after assemble)
TTL: 24h (configurable)
```

### 4.3 Ingestion Logic (Controller)
```php
public function ingest(Request $r, TruthStore $store, TruthAssembler $asm)
{
    $family = strtolower($r->query('f', 'truth'));  // 'er'|'truth'
    $version = (string) $r->query('v', '1');
    $code = (string) $r->query('code');
    $i = (int) $r->query('i');
    $n = (int) $r->query('n');
    $p = (string) $r->query('p', '');
    $t = (string) $r->query('t', 'raw');            // transport

    // Validate
    if (!$code || $i < 1 || $n < 1 || $i > $n) {
        abort(422, 'Invalid envelope parameters.');
    }

    $store->initIfMissing($code, $family, $version, $n, $t);
    $store->putChunk($code, $i, $p);

    // Optionally: attempt assemble if complete (to pre-compute artifact cache)
    if ($store->isComplete($code)) {
        $asm->assembleAndCache($code); // throws on corruption/mismatch
        return redirect()->to("/truth/render/{$code}");
    }

    return redirect()->to("/truth/collect/{$code}");
}
```

### 4.4 Assembly Service
- Reads chunks `1..N`, concatenates payload in order.
- **Transport decode** (base64url/gzip as needed).
- **Payload decode** by **AutoDetectSerializer** (JSON/YAML).
- Validates high-level shape (type, code, schema if available).
- Caches the **artifact** and marks session complete.

### 4.5 Progress API
```jsonc
GET /truth/progress/XYZ
{
  "code": "XYZ",
  "total": 5,
  "received": [1,2,3],
  "missing": [4,5],
  "complete": false,
  "family": "truth",
  "version": "1",
  "transport": "b64u"
}
```

### 4.6 Security
- **Rate limit** ingest by IP + code.
- TTL for session keys (e.g., 24h).
- Input validation for `f,v,code,i,n,p,t`.
- Optional **signature** chunk later (Phase 4).

---

## 5) Frontend (Vue/Inertia)

### 5.1 `/truth/collect/:code`
- Shows progress bar & “Scan next” instructions.
- Polls `/truth/progress/:code` every few seconds (or uses SSE/Reverb).
- CTA: “Open camera to scan next QR” (or open in-page scanner).

### 5.2 `/truth/render/:code`
- Fetches artifact JSON/YAML from `/api/truth/artifact/:code` (or SSR).
- Renders: **TruthER.vue**, **TruthBallot.vue**, or **TruthCanvass.vue** based on `artifact.type`.
- Provides **Print**, **Save JSON/YAML**, and **Share link**.

> The artifact object should include minimal header: `{ type: 'ER'|'BAL'|'CAN', code: 'XYZ', data: {...} }`

---

## 6) Transport & Payload

- **Transport** (outer): raw | **base64url** | **gzip+base64url**
    - Recommended: **base64url**, optionally gzip for large payloads.
- **Payload** (inner): JSON or YAML (AutoDetect).
- **Backward Compatibility:** accept `ER|v1|...` lines & URL `f=er` in addition to `TRUTH|v1|...` & `f=truth`.

---

## 7) Error Handling & UX

- **Duplicate** chunk → overwrite or ignore (configurable); warn in logs.
- **Mismatched totals** or **mixed codes** → reject & display actionable message.
- **Corrupted payload** → mark session invalid; allow a **Reset**.
- If the user lands on **/render/:code** but session incomplete → redirect to **/collect/:code**.

---

## 8) Observability

- Metrics: ingests per code, completion rate, average time to completion.
- Logs: invalid envelopes, malformed transport, decode failures.
- Optional analytics: UTM params on the ingest URL to track entry points.

---

## 9) Testing Matrix (Pest)

- ✅ Success: N of N chunks → artifact matches original payload.
- ✅ Mixed order ingestion (2,1,3,...) → still assembles correctly.
- ✅ Legacy ER|v1 acceptance (line & URL forms).
- ❌ Negative: mixed `code`, mismatched `n`, out-of-range `i`, corrupted `p`.
- ❌ Negative: transport mismatch (e.g., `t=gz+b64u` but not actually gzipped).

---

## 10) Rollout

1. **Phase 1 (Core):** Codec + URL ingest + Redis store + Render page.
2. **Phase 2 (Front):** Smooth collector UX, in-browser scanner, progress visualizations.
3. **Phase 3 (Writers):** Generate QR sets (PNG/PDF) embedding the URL envelope.
4. **Phase 4 (Security):** `sig` chunk for integrity + replay protection.
5. **Phase 5 (Migration):** Writers switch default to `TRUTH|v1`. Readers continue to accept legacy `ER|v1`.

---

## 11) Reference Snippets

### 11.1 Route wiring
```php
Route::prefix('truth')->group(function () {
    Route::get('/ingest', [TruthIngestController::class, 'ingest'])->name('truth.ingest');
    Route::get('/collect/{code}', [TruthCollectController::class, 'show'])->name('truth.collect');
    Route::get('/progress/{code}', [TruthProgressController::class, 'json'])->name('truth.progress');
    Route::get('/render/{code}', [TruthRenderController::class, 'show'])->name('truth.render');
});
```

### 11.2 Store Interface (Redis)
```php
interface TruthStore {
    public function initIfMissing(string $code, string $family, string $version, int $total, string $transport): void;
    public function putChunk(string $code, int $i, string $payloadPart): void;
    public function isComplete(string $code): bool;
    /** @return string[] indexed 1..N or sparse if incomplete */
    public function getChunks(string $code): array;
    public function setArtifact(string $code, string $blob): void;
    public function getArtifact(string $code): ?string;
    /** @return array{received:int[], missing:int[], total:int} */
    public function status(string $code): array;
}
```

### 11.3 Assembler Service
```php
class TruthAssembler {
    public function __construct(
        private TransportCodec $transport,
        private PayloadSerializer $serializer, // Auto-detect (JSON/YAML)
        private TruthStore $store
    ){}

    public function assembleAndCache(string $code): array {
        $chunks = $this->store->getChunks($code);
        ksort($chunks);
        $packed = implode('', $chunks);        // join transport fragments
        $raw = $this->transport->decode($packed);
        $payload = $this->serializer->decode($raw);

        // validate
        if (!is_array($payload) || !isset($payload['type'], $payload['code'], $payload['data'])) {
            throw new \InvalidArgumentException('Invalid TRUTH payload structure');
        }
        if ($payload['code'] !== $code) {
            // soft warning; allow if you expect external codes, else enforce
        }

        $this->store->setArtifact($code, json_encode($payload));
        return $payload;
    }
}
```

---

## 12) Future Extensions

- **Presence channels** (Reverb) on `/collect/:code` for real-time progress.
- **Offline QR packs** (ZIP/PDF) with embedded `truth:` custom scheme deep link fallback.
- **Signature chunk** (`sig`) with detached proof (Phase 4).
- **Multi-artifact batches** under one `code` with sub-IDs (e.g., `code=XYZ&part=ER`/`BAL`).

---

**That’s it.**  
This plan gives you a **camera-native, app-less** ingestion path from printed QRs to verifiable data reconstruction and rendering—complementing the existing TRUTH chunking/assembly pipeline.

