# QR Chunk Decode API

This API accepts multiple QR code chunk strings, reconstructs the original JSON payload, and returns the decoded data.  
It is designed for use by external scanning applications that split large JSON data into multiple QR codes.

---

## Endpoint

```
POST /qr/decode
```

---

## Request Body

**Content-Type**: `application/json`

### Fields

| Field         | Type        | Required | Description |
|---------------|-------------|----------|-------------|
| `chunks`      | `string[]`  | ✅ Yes   | An array of QR chunk strings in the format `ER|v1|<CODE>|<i>/<N>|<PAYLOAD>` |
| `persist`     | `boolean`   | ❌ No    | If `true`, the decoded files will be persisted to storage (`storage/app/qr_decodes/<CODE>/<timestamp>/`) |
| `persist_dir` | `string`    | ❌ No    | Optional subfolder name under the `<CODE>` directory when persisting |

---

### Chunk Format

Each chunk string must follow the pattern:

```
ER|v1|<CODE>|<i>/<N>|<PAYLOAD>
```

Where:

| Segment     | Description |
|-------------|-------------|
| `ER`        | Fixed prefix indicating Election Return QR |
| `v1`        | Version string |
| `<CODE>`    | Unique code identifying the ER set |
| `<i>`       | Chunk index (1-based) |
| `<N>`       | Total number of chunks |
| `<PAYLOAD>` | Base64URL-encoded compressed binary data (gzdeflate) |

---

## Example Request

```http
POST /qr/decode HTTP/1.1
Content-Type: application/json

{
  "chunks": [
    "ER|v1|ABC123|1/3|eJyrVkrLz1eyUkpKLFKqBQAxxgRy",
    "ER|v1|ABC123|2/3|zshJrShRKMpMUbJSi1KqBQBjlgWW",
    "ER|v1|ABC123|3/3|UsovSS0qzszPAwBS4QeX"
  ],
  "persist": true,
  "persist_dir": "scanner-20250815"
}
```

---

## Response

**Status Code**: `200 OK`

**Content-Type**: `application/json`

### Fields

| Field              | Type        | Description |
|--------------------|-------------|-------------|
| `code`             | `string`    | The QR set code (`<CODE>`) |
| `version`          | `string`    | Version string (`v1`) |
| `total`            | `integer`   | Total chunks expected (`<N>`) |
| `received_indices` | `int[]`     | Indices of chunks received |
| `missing_indices`  | `int[]`     | Indices still missing (empty if fully received) |
| `json`             | `object?`   | Decoded JSON object (only present if all chunks received and valid) |
| `raw_json`         | `string?`   | Raw JSON string (included only when persisted) |
| `persisted_to`     | `string?`   | Absolute server path to the persisted directory (included if `persist` is `true`) |

---

### Example Response (Complete Set)

```json
{
  "code": "ABC123",
  "version": "v1",
  "total": 3,
  "received_indices": [1, 2, 3],
  "missing_indices": [],
  "json": {
    "precinct": "123-A",
    "votes": [
      {"candidate": "John Doe", "count": 120},
      {"candidate": "Jane Smith", "count": 95}
    ]
  },
  "raw_json": "{\n  \"precinct\": \"123-A\",\n  \"votes\": [\n    {\"candidate\": \"John Doe\", \"count\": 120},\n    {\"candidate\": \"Jane Smith\", \"count\": 95}\n  ]\n}",
  "persisted_to": "/full/path/to/storage/app/qr_decodes/ABC123/scanner-20250815"
}
```

---

### Example Response (Incomplete Set)

```json
{
  "code": "ABC123",
  "version": "v1",
  "total": 3,
  "received_indices": [1, 2],
  "missing_indices": [3],
  "json": null
}
```

---

## Error Responses

| Status | Message | Cause |
|--------|---------|-------|
| `422`  | `No chunks provided.` | `chunks` is empty |
| `422`  | `Invalid chunk format.` | Chunk does not match the required `ER|v1|<CODE>|<i>/<N>|<PAYLOAD>` structure |
| `422`  | `Invalid index/total segment.` | `<i>/<N>` segment is not valid |
| `422`  | `Index X cannot exceed total Y.` | Index greater than total chunks |
| `422`  | `Mismatched code '...'` | Different `<CODE>` found in different chunks |
| `422`  | `Mismatched version '...'` | Different version strings |
| `422`  | `Mismatched total ...` | Conflicting `<N>` values |
| `422`  | `Duplicate chunk #X has conflicting payload.` | Same chunk index has different payload content |
| `422`  | `Failed to inflate payload (corrupt data?).` | Compressed data cannot be decompressed |
| `422`  | `Decoded payload is not valid JSON.` | Successfully decompressed but invalid JSON |

---

## Notes for Scanner Developers

- **Order of chunks is not required** in the upload; the server will reorder them.
- **Partial uploads are supported** — the API will return `missing_indices` until all chunks are received.
- **Compression format**: The `<PAYLOAD>` is `gzdeflate()`-compressed binary, encoded in Base64URL (RFC 4648, §5).
- **Persistence**:
    - When `persist` is `true`, all original chunk strings, the raw JSON, and a manifest will be stored.
    - This is useful for debugging or archiving.
- **Base64URL padding** is handled automatically by the server.
