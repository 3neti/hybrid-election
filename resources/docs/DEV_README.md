# QR Chunk Scanner & Uploader Developer Guide

## Overview
This guide is for developers building a **separate QR code scanner/uploader** tool that reads multiple QR code chunks, reconstructs the original JSON, and sends it to our backend for decoding.

Our backend provides an endpoint:

```
POST /qr/decode
```

which accepts an array of QR chunk strings in the format:

```
ER|v1|<CODE>|<i>/<N>|<PAYLOAD>
```

Where:
- **`ER`** → fixed prefix
- **`v1`** → version
- **`<CODE>`** → unique identifier for the dataset (e.g., precinct ID)
- **`<i>`** → chunk index (1-based)
- **`<N>`** → total number of chunks
- **`<PAYLOAD>`** → Base64URL-encoded, DEFLATE-compressed JSON segment

---

## API Reference

### Endpoint
**`POST /qr/decode`**

### Request Body
```json
{
  "chunks": [
    "ER|v1|ABC123|1/3|eJzLKCkpKLbS10/KK83JBAA7zwZ1",
    "ER|v1|ABC123|2/3|QK84vLQw0BIA",
    "ER|v1|ABC123|3/3|U4hIzcnJVyjPL8pJAQA="
  ],
  "persist": true,
  "persist_dir": "test_upload"
}
```

**Fields:**
- **`chunks`** (string[]) – Required. Ordered or unordered QR chunks in text form.
- **`persist`** (boolean) – Optional. If `true`, the server saves decoded JSON and inputs in `/storage/app/qr_decodes/<CODE>/<timestamp>/`.
- **`persist_dir`** (string) – Optional. Saves to a specific subfolder.

---

### Example Response (all chunks received)
```json
{
  "code": "ABC123",
  "version": "v1",
  "total": 3,
  "received_indices": [1, 2, 3],
  "missing_indices": [],
  "json": {
    "precinct": "0001A",
    "votes": {
      "CANDIDATE1": 123,
      "CANDIDATE2": 456
    }
  },
  "raw_json": "{\"precinct\":\"0001A\",\"votes\":{\"CANDIDATE1\":123,\"CANDIDATE2\":456}}",
  "persisted_to": "/full/path/to/storage/app/qr_decodes/ABC123/test_upload"
}
```

---

## Test Payload Generator

We provide a PHP script to simulate chunk creation from a sample JSON file.

### PHP Version
**File:** `generate-test-chunks.php`
```php
<?php
$file = $argv[1] ?? null;
$maxChars = (int)($argv[2] ?? 1200);

if (!$file || !file_exists($file)) {
    fwrite(STDERR, "Usage: php generate-test-chunks.php <file.json> [max_chars_per_qr]\n");
    exit(1);
}

$raw = file_get_contents($file);
$deflate = gzdeflate($raw, 9);
$b64url = rtrim(strtr(base64_encode($deflate), '+/', '-_'), '=');

$total = (int)ceil(strlen($b64url) / $maxChars);
$code = strtoupper(bin2hex(random_bytes(3)));
$version = 'v1';

$chunks = [];
for ($i = 1; $i <= $total; $i++) {
    $payload = substr($b64url, ($i - 1) * $maxChars, $maxChars);
    $chunks[] = "ER|{$version}|{$code}|{$i}/{$total}|{$payload}";
}

echo json_encode(['chunks' => $chunks], JSON_PRETTY_PRINT);
```

Run:
```bash
php generate-test-chunks.php sample-er.json 1200 > test_payload.json
```

---

### Shell Version (Auto-installs PHP if missing)
**File:** `generate-test-chunks.sh`
```bash
#!/usr/bin/env bash
set -e

if ! command -v php >/dev/null 2>&1; then
    echo "PHP not found. Please install PHP >= 8.1."
    exit 1
fi

php generate-test-chunks.php "$@"
```

Usage:
```bash
chmod +x generate-test-chunks.sh
./generate-test-chunks.sh sample-er.json 1200 > test_payload.json
```

---

## Testing the Upload

Once you have `test_payload.json`:

```bash
curl -X POST https://hybrid-election-main-84pebv.laravel.cloud/qr/decode      -H "Content-Type: application/json"      -d @test_payload.json
```

Or with `persist`:
```bash
curl -X POST https://hybrid-election-main-84pebv.laravel.cloud/qr/decode      -H "Content-Type: application/json"      -d '{
           "chunks": ["ER|v1|ABC123|1/3|...", "...", "..."],
           "persist": true,
           "persist_dir": "test_upload"
         }'
```

---

## Notes for Developers
- Chunks can be sent **in any order**. The server reorders and validates them.
- If chunks are missing, `missing_indices` will list them.
- All chunks must have:
    - Same `code`
    - Same `version`
    - Same total count
- **Base64URL encoding** is used, with `-` and `_` replacing `+` and `/`, and `=` padding removed.
- The payload is DEFLATE-compressed JSON.
