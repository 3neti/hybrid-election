# Truth QR PHP – End-to-End Flow & Test Coverage

## 1) Dependencies & Bindings (Bootstrapping)
- **truth-codec-php** provides:
    - `Envelope` (V1 line/URL)
    - `PayloadSerializer` (JSON/YAML/AutoDetect)
    - `TransportCodec` (identity/base64url/gzip…)
- **truth-qr-php** provides:
    - `TruthStore` (Array/Redis) to persist partial chunks + artifact
    - `TruthAssembler` (high-level orchestration)
    - `TruthQrWriter` implementations (Null, Bacon v3)
    - HTTP controller + CLI command

Service provider binds defaults (e.g., `TruthQrWriter` → Null or Bacon; `TruthAssemblerContract` → `TruthAssembler`; `TruthStore` → Redis/Array).

---

## 2) Writing QR Codes (Outbound)
- Prepare a DTO (ER/BAL/Canvass), run through **codec**:
    1. `PayloadSerializer::encode(payload)` → string
    2. `TransportCodec::encode(blob)` → packed string
    3. `EnvelopeV1Line|Url::header(code,i,n, part)` → N lines/URLs
- `TruthQrWriter::write(lines)` turns lines into QR images (SVG/PNG):
    - **NullQrWriter**: `"qr:<fmt>:<line>"`
    - **BaconQrWriter**: renders **SVG** by default; **PNG** via Imagick/GD

---

## 3) Ingesting Scans (Inbound)
- Scanner POSTs encoded line/URL:
    - `POST /truth/ingest` → controller → `TruthAssembler->ingestLine($line)`
        - Parse envelope → `[code, index, total, fragment]`
        - Persist fragment in `TruthStore`
        - Return `status(code)` = `{ code, total, received, missing }`
- Poll with `GET /truth/status/{code}`.

---

## 4) Assembling & Retrieving the Artifact
- When complete:
    - `TruthAssembler->assemble(code)` joins parts, decodes, caches artifact.
- Download with:
    - `GET /truth/artifact/{code}` → streams artifact (`application/json|yaml`).

---

## 5) CLI Ingest (Batch/Files)
- `php artisan truth:ingest-file path [--code=] [--print] [--out=]`
    - Reads lines, ingests each,
    - Prints progress,
    - If complete: writes cached artifact (`body + mime`) to `--out`.

---

# Current Test Coverage

### Writers
- **NullQrWriter**: deterministic outputs.
- **BaconQrWriter**:
    - SVG happy path (`<svg … </svg>`)
    - PNG happy path (Imagick/GD, skips if missing)

### Controller
- Ingest + artifact using **FakeTruthAssembler**:
    - Ingest lines, assert artifact available with JSON content type.

### CLI
- `truth:ingest-file`: feeds lines, asserts exit code 0, file written with artifact.

---

# Why Artifact Assertion Changed
- Store standardized artifact as:
  ```php
  ['mime' => 'application/json', 'body' => <serialized>]
  ```
- Tests updated from `['content_type','content']` → `['mime','body']`.

---

# Are Tests Enough?
Good coverage for happy paths. Suggested additions:
1. **Envelope parsing errors** (bad input → 422/400).
2. **Status mid-progress** (missing indexes).
3. **Artifact 404** before complete.
4. **Assembler decode failure** path.
5. **Writer fallback** when no backends exist.
6. **Redis store integration** (optional).
7. **URL envelope ingestion** (truth:// deep link or https).

---

# TL;DR
- Flow: encode → QR write → ingest → assemble → artifact.
- Tests: validate writers, ingest flow, CLI happy path.
- Next: add negative/edge cases + Redis + URL ingestion to lock scope.
