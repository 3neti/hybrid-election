# Plan: `truth-qr-php` (Laravel package)

## 1) Scope & Dependencies
- **Purpose:** Generate & render QR sets for TRUTH payloads (Ballot, ER, Canvass) and provide the ingest/collect/render web UX.
- **Depends on:** `truth-codec-php` for serialization, transport, envelope parsing/formatting; Laravel 12; Redis (or cache) for session storage; a QR lib (e.g., `endroid/qr-code`).

## 2) Package Layout
```
packages/truth-qr-php/
  composer.json
  src/
    Http/
      Controllers/
        TruthIngestController.php
        TruthCollectController.php
        TruthProgressController.php
        TruthRenderController.php
    Services/
      TruthStore.php           // interface
      RedisTruthStore.php      // Redis-backed impl
      TruthAssembler.php       // uses codec: assemble/cached artifact
      QrWriter.php             // renders PNG/PDF grids for chunks
    Support/
      UrlBuilder.php           // builds deep-link & web-link envelopes
    Providers/
      TruthQrServiceProvider.php
    Resources/
      views/collect.blade.php
      views/render.blade.php
  routes/web.php
  config/truth-qr.php
  tests/Pest.php
  tests/Feature/
    IngestFlowTest.php
    UrlEnvelopeCompatibilityTest.php
  tests/Unit/
    QrWriterTest.php
    UrlBuilderTest.php
    RedisTruthStoreTest.php
```

## 3) Key Interfaces & Services

### TruthStore (session storage)
- `initIfMissing(code, family, version, total, transport): void`
- `putChunk(code, i, payloadPart): void`
- `isComplete(code): bool`
- `getChunks(code): array`
- `status(code): array{received:int[], missing:int[], total:int}`
- `setArtifact(code, blob): void`
- `getArtifact(code): ?string`

> Default: **RedisTruthStore**, TTL configurable (e.g., 24h). Mirrors the progress/assembly design in your URL plan.

### TruthAssembler
- Inject: `TransportCodec`, `PayloadSerializer` (auto-detect), `TruthStore`.
- `assembleAndCache(code): array` → joins payload parts, decodes via transport + serializer, validates shape, caches artifact. (Shape checks align with “TRUTH Engine” DTO envelope guidance.)

### QrWriter
- Inputs: array of chunk **lines** (from `ChunkEncoder` + chosen Envelope).
- Renders:
    - **PNG sheet(s)** (grid of QR codes + per-QR caption),
    - **Optional PDF** (with print margins, cut guides).
- Accepts both **line** and **URL** envelopes; you’ll likely default to **URL** for “tap-to-ingest”.

### UrlBuilder
- Given `(code, i, n, payloadPart, transport)`, returns:
    - **Deep link**: `truth://v1/<prefix>/<code>/<i>/<n>?c=...`
    - **Web link**: `https://host/truth/ingest?...` (from config)
- Reuses the **Envelope V1 URL** implementation from the codec (config-driven), but this helper centralizes writer-side concerns (UTM tags, extra hints, etc.).

## 4) HTTP Endpoints (package routes)
- `GET /truth/ingest` → **TruthIngestController@ingest**
    - Validates `f,v,code,i,n,p(ayload),t(transport)`,
    - Persists chunk,
    - If complete → `redirect('/truth/render/{code}')`, else → `redirect('/truth/collect/{code}')`.
- `GET /truth/collect/{code}` → progress page (poll `/truth/progress/{code}`).
- `GET /truth/progress/{code}` → JSON status for polling/SSE.
- `GET /truth/render/{code}` → fetch artifact (from store), render ER/BAL/CAN view.

> These are exactly the flow points in your URL ingestion plan.

## 5) Config (config/truth-qr.php)
- `web_base` (e.g., `https://example.org/truth/ingest`)
- `qr.size`, `qr.margin`, `grid.cols`, `grid.rows`
- `store.driver` = redis | array (testing)
- `ttl_seconds`
- `link.transport` default = `b64u` or `gz+b64u`
- `envelope.prefix` & `envelope.version` (should defer to codec config for consistency)

## 6) Wiring to `truth-codec-php`
- Composer require your codec package.
- In `TruthQrServiceProvider`:
    - bind `TruthStore` → `RedisTruthStore`
    - bind `QrWriter`
    - you *use* codec singletons for `PayloadSerializer`, `TransportCodec`, and `Envelope` (line/url), which the codec provider already binds.

## 7) Writer side (simple API)
- `QrWriter::renderPngGrid(chunks: string[], options): Image[]`
- `QrWriter::renderPdf(chunks: string[], options): string` (path)
- Helper to **choose envelope**:
    - line: `EnvelopeV1Line` (legacy/diagnostic),
    - url: `EnvelopeV1Url` (default for camera workflows).

## 8) Tests (Pest)

### Unit
- **UrlBuilderTest**: ensures emitted URLs match scheme/query (deep link + web link).
- **QrWriterTest**: asserts PNG bytes exist & size ~expected; PDF generated.
- **RedisTruthStoreTest**: init/put/status completeness.

### Feature
- **IngestFlowTest**:
    1) Generate N chunks via codec (URL envelope),
    2) Hit `/truth/ingest?…` N times (out-of-order),
    3) Poll `/truth/progress/{code}` → `complete=true`,
    4) GET `/truth/render/{code}` returns 200 + artifact JSON.
- **UrlEnvelopeCompatibilityTest**:
    - Accept both `f=er` legacy and `f=truth`,
    - Accept `ER|v1|…` lines POSTed as fallback (optional).

> The test flow mirrors the end-to-end described in the URL Envelope doc.

## 9) Minimal Views
- **collect.blade.php**: clean progress bar, received/missing, “Open camera / Continue scanning”.
- **render.blade.php**: SSR basic JSON payload and dispatch to your existing Vue components (TruthER / TruthBallot / TruthCanvass) if your app uses Inertia.

## 10) Rollout
1) Implement Store + Assembler + Controllers + Routes.
2) Add basic Blade views + progress polling.
3) Integrate `QrWriter` (PNG first; PDF later).
4) Wire to your app: publish config, route group, Redis connection.
5) Ship initial E2E tests & smoke test with a real chunk set.
