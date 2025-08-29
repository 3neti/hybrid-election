# Playground Plus Enhancement Plan
Robust improvements for the Truth QR UI playground that add visual QR rendering, configurable envelope (prefix & version), better multi‑part UX, scanner polish, advanced codec options, error handling, and persistence.

> **Scope**: Everything here lives in the `truth-qr-ui` package, with minimal assumptions about the host app. All UI wiring is behind the package’s route prefix (configurable).

---

## 0) Goals & Principles
- **Prod‑safe**: No reliance on container bindings inside actions; use explicit constructors or the `CodecAliasFactory`.
- **Feature parity**: Encode/Decode via actions _and_ controllers; Playground consumes those HTTP endpoints.
- **Composability**: Vue components remain small and testable; composables isolate side effects.
- **Backwards compatible**: Defaults behave exactly like today until the user changes options.

---

## 1) Visual QR Rendering in Playground
### 1.1 Server‑side
- **EncodePayload** already supports optional `TruthQrWriter`. Expose a **writer toggle** and **format** (svg/png) in UI.
- Map UI to `writer_fqcn` via **CodecAliasFactory** (e.g. `bacon(svg)`, `endroid(png|svg)` → writer FQCN).

### 1.2 Client‑side (Vue)
- In `Playground.vue`:
    - After encode, if `qr` exists, render a list of `<img>` when PNG or `<div v-html="svg">` for SVG.
    - Add a **“Download all”** button (zip via client only if needed; otherwise per‑image download).
- **Edge cases**: multi‑part payloads; preserve order labels (1/ N).

### 1.3 Tests
- HTTP tests: assert `qr` present when writer is requested and confirm first item looks like `<?xml` (svg) or `iVBOR` (png).
- Action/unit: keep explicit constructor usage; verify determinism across runs for same inputs.

---

## 2) Envelope Configuration (Prefix + Version)
### 2.1 Backend
- Extend `CodecAliasFactory::makeEnvelope(alias, { prefix?: string, version?: string })` to pass optional init args to envelope classes that support them (e.g. `EnvelopeV1Url`, `EnvelopeV1Line`).
- Actions remain unchanged: they accept a concrete `Envelope`. Controllers: read `prefix` and `version` from request, forward to factory.

### 2.2 Frontend
- Add **Prefix** text input (default `ER`) and **Version** select (`v1` for now; prepared for future versions).
- Show the resulting line/URL header in a preview gutter (e.g., `truth://v1/ER/...` or `ER|v1|...`).

### 2.3 Tests
- Controller tests: send `prefix=ERTEST001`, `version=v1` and assert encoded lines start with the adjusted header.

---

## 3) Multi‑part UX
- Show a **segments progress** (e.g., chips: `1/5, 2/5, ...`), and a **copy-all** button.
- Provide **“Simulate missing chunk”** toggle in Playground for demo purposes; ensure Decode endpoint reports `missing` accordingly.
- Add **“Reorder randomly”** to simulate scan order; ensure session still assembles.

**Tests**
- Encode small size to force chunking; drop one index; ensure `missing` reports that index; with the chunk re-added, `complete=true` and payload equals original.

---

## 4) Scanner UX Polish
- Keep `qr-scanner` with camera selector, graceful fallback, and permission prompts.
- Show **live assembly status** (`received`, `missing`), and **payload preview** once complete.
- Add **“Reset session”** button; emit event to clear `ClassifySession` state client‑side.

**Tests**
- Controller e2e: loop through lines in random order via multiple POSTs to `/api/decode`, confirm status converges to complete.

---

## 5) Advanced Codec Options
- **Transports**: `base64url`, `base64url+deflate`, `base64url+gzip` (present), configurable via aliases in UI.
- **Serializers**: `json`, `yaml`, `auto` (AutoDetect wraps json/yaml).
- **Writers**: `bacon(svg|png)`, `endroid(svg|png)` (behind feature availability checks).

**Tests**
- Dataset‑style Pest tests to combine serializer × transport × envelope, verifying round‑trips.

---

## 6) Error Handling & Validation
- Strict input checks in controllers:
    - Encode: `payload` required; string payload must decode to non‑empty object.
    - Decode: `lines` or `chunks` required; if both missing → 422.
- On decode conflicts/corruption: return 422 with concise message or 200 `{complete:false, missing:[...]}` based on assembler behavior; tests already tolerate both.
- UI: toast/snackbar on HTTP 4xx/5xx, inline error summary.

---

## 7) Persistence & Download
- **Optional** in‑memory history on the client: “encoded” and “decoded” runs (for demo UX).
- Export:
    - **Download lines**: `.txt` or `.json` (array).
    - **Download QR images**: individual saves; optional zip.
- Copy to clipboard utilities.

**Tests**
- Unit testing for text builders (no need to assert actual downloads).

---

## 8) API Additions (Controllers)
- **EncodeController** (thin proxy) → calls `EncodePayload::asController`.
- **DecodeController** (thin proxy) → calls `DecodePayload::asController`.
- Dedicated **PlaygroundController** → returns Inertia page only.

All three exist; align request shapes and alias names with `CodecAliasFactory`.

---

## 9) Config & Routing
- Config key: `truth-qr-ui.prefix` (default: `truth-qr-ui`).
- Routes: publishable; ensure `web` middleware and CSRF token attached for POSTs.
- Document host requirements: **Inertia + Vue 3** must be installed in the host app; the package provides views + page components.

---

## 10) File Map (Package)
- `src/Actions/EncodePayload.php` (already)
- `src/Actions/DecodePayload.php` (already)
- `src/Support/CodecAliasFactory.php` (already) → extend for envelope options
- `src/Http/Controllers/EncodeController.php` (already)
- `src/Http/Controllers/DecodeController.php` (new)
- `src/Http/Controllers/PlaygroundController.php` (already)
- `resources/js/Pages/Playground.vue` (new)
- `resources/js/components/…` (QrPreview.vue, ScannerPane.vue, CodecForm.vue, StatusChips.vue)
- `resources/js/composables/…` (useEncode.ts, useDecode.ts, useScanner.ts, useClipboard.ts)
- `resources/views/app.blade.php` or Inertia layout (if needed)

---

## 11) Test Plan Summary
- **Actions**: Encode/Decode happy paths + edge cases (invalid payloads, chunk loss, conflicts).
- **Controllers**: Alias vs FQCN coverage; envelope prefix/version; writer on/off; multi‑part behavior.
- **E2E**: encode → shuffle → decode loop until complete; assert payload equality.
- **Datasets**: serializer × transport × envelope grids; skip writers if class not present.

---

## 12) Rollout Checklist
- [ ] Extend `CodecAliasFactory` to accept envelope options (`prefix`, `version`).
- [ ] Encode/Decode controllers: plumb `prefix`/`version` from request.
- [ ] Playground.vue: add envelope/transport/serializer/writer controls.
- [ ] Add QR preview list (SVG/PNG).
- [ ] Add multi‑part widgets (progress chips, re‑order, simulate missing).
- [ ] Scanner polish (camera, reset, status HUD).
- [ ] Add client download helpers (lines/QRs).
- [ ] Tests: add dataset coverage and controller edge cases.
- [ ] Update README & docs.

---

## 13) Nice‑to‑Have (Future)
- Persist sessions in localStorage for browser refresh survival.
- Zip server‑side with a small endpoint if needed.
- Shareable links to pre‑load payload demo presets.
- Dark mode and a11y checks.

---

### References
- `TruthQrUi\Actions\EncodePayload` & `DecodePayload` (explicit‑constructor approach)
- `TruthQrUi\Support\CodecAliasFactory` (aliases → FQCNs)
- `TruthQr\TruthQrPublisher` and `TruthQr\Classify\Classify` (publish/assemble)
- Inertia + Vue 3 (host app) — package ships the pages/components
