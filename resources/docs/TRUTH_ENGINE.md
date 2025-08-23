# TRUTH Engine — Encoding and Decoding DTOs (Ballot, ER, Canvass)

## Introduction
The **TRUTH Engine** is a transport-agnostic system for encoding and decoding election artifacts into portable, verifiable units that can be shared via QR, paper, NFC, or digital files.

It generalizes your current Election Return (ER) QR encoding into a broader framework for:
- **Ballots** (individual voter submissions),
- **Election Returns (ERs)** (precinct-level tallies),
- **Canvass** (collections of ERs for a higher scope).

The principle:  
**Canonicalize → Hash → Compress → Encode → Chunk**.  
On the receiving end: **Collect → Reassemble → Decode → Verify → Consume as DTO**.

---

## 1. The TRUTH Envelope
Every payload is wrapped in a small, versioned, self-describing envelope:

```jsonc
{
  "kind": "ballot | er | canvass",
  "v": 1,
  "code": "C3VJOY0NVVHP",
  "uid": "ulid-12345",
  "issuer": {
    "system": "tally_ui",
    "precinct_code": "CURRIMAO-001"
  },
  "created_at": "2025-08-23T07:00:00Z",
  "hash": {
    "alg": "sha256",
    "value": "<hex-or-base64url>"
  },
  "sig": {
    "mode": "otp | qr | scratch | none",
    "value": "user:uuid-juan,signature:…"
  },
  "payload": { /* DTO (Ballot, ER, Canvass) */ }
}
```

### Benefits
- **`kind`**: lets one engine support multiple DTOs.
- **`hash`**: integrity check over canonical payload.
- **`sig`**: optional lightweight signing (per DIGITAL_SIGNATURE.md).
- **`uid`**: globally unique identifier for replay prevention.
- **Versioned**: both at envelope and DTO level.

---

## 2. Canonicalization and Multi-Format Support
Payloads must be canonicalized before hashing to ensure deterministic verification.

Supported formats:
- **JSON** (default): Canonical JSON (sorted keys, stable formatting).
- **YAML**: Converted to canonical JSON before hashing, but stored/transported as YAML if desired.

Pipeline:
1. Accept object (DTO) in **native structure**.
2. If YAML: parse → convert → canonical JSON string.
3. If JSON: canonicalize directly.
4. Hash, compress, encode.

This way both JSON and YAML payloads map to the **same canonical hash**.

---

## 3. Encoding (Chunked Transport)
After canonicalization + compression, the payload is encoded into QR-friendly chunks.

### Chunk Header
```
TRUTH|v1|<code>|<kind>|<i>/<N>|<base64url-payload>
```

- `TRUTH|v1`: marker + envelope version.
- `<code>`: ER or ballot code.
- `<kind>`: `ballot`, `er`, or `canvass`.
- `<i>/<N>`: chunk number of total.
- `<payload>`: compressed base64url block.

Example:
```
TRUTH|v1|C3VJOY0NVVHP|er|3/5|fsogub9W...
```

---

## 4. Decoding Pipeline
1. Parse headers, ensure consistent `code`, `kind`, `N`.
2. Collect all chunks `1..N`.
3. Concatenate → base64url decode → inflate → parse JSON/YAML.
4. Verify payload hash against `envelope.hash.value`.
5. Validate DTO type (`BallotData`, `ElectionReturnData`, `CanvassData`).
6. Optionally verify signature (`sig`).

---

## 5. DTO Payloads

### Ballot TRUTH
```ts
interface BallotData {
  id: string
  code: string
  precinct_code: string
  votes: VoteData[]
}
```

### ER TRUTH
Your existing `ElectionReturnData`.

### Canvass TRUTH
```ts
interface CanvassData {
  id: string
  code: string
  scope: { level: 'municipality'|'province'|'nation', code?: string }
  returns: ElectionReturnData[]
  aggregates?: Array<{ position_code: string, candidate_code: string, count: number }>
  created_at: string
}
```

---

## 6. Security Posture
- **Integrity**: via `hash`.
- **Provenance**: via `sig` (optional OTP/QR signature).
- **Replay protection**: via `uid` + audit logs.
- **Audit**: every decode attempt logged with outcome.

---

## 7. Libraries and APIs
Provide a shared library across TS (frontend) and PHP (backend):

### Core API
```ts
encodeEnvelope(kind: 'ballot'|'er'|'canvass', dto: object, opts: { format?: 'json'|'yaml' }): string[]
decodeChunks(lines: string[]): { envelope: object, payload: object }
hashPayload(payload: object): string
validate(kind: string, payload: object): boolean
```

### File/Format Handling
- Input can be JSON or YAML.
- Canonicalization ensures same hash regardless of format.

---

## 8. Deployment Plan

### Phase 1 — Core
- Implement `TruthCodec` (TS + PHP).
- Unit tests with JSON & YAML vectors.

### Phase 2 — Frontend
- Extend `useChunkAssembler` → generic TRUTH assembler.
- Add `TruthBallot.vue`, `TruthER.vue`, `TruthCanvass.vue`.

### Phase 3 — Writers
- Export TRUTH as QR sets (PNG/PDF).
- Export as file (JSON or YAML).

### Phase 4 — Security
- Add `sig` support with OTP or scratchpads.
- Integrate replay protection & audit.

### Phase 5 — Migration
- Accept legacy `ER|v1|...`.
- Writers emit `TRUTH|v1|...` going forward.

---

## Conclusion
The TRUTH Engine generalizes your QR-based election verification into a **multi-format, multi-kind envelope system**.  
It allows **ballots, ERs, and canvasses** to be faithfully reconstructed from QR or file form, validated against their canonical hashes, and consumed by strongly typed DTOs.

It is designed to be:
- **Transparent**: deterministic encoding/decoding.
- **Flexible**: supports JSON, YAML, QR, file, or network transport.
- **Future-proof**: envelope and DTO versioning.
- **Secure**: optional signatures, replay prevention, audit trails.  
