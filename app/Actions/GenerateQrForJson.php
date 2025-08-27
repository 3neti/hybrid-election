<?php

namespace App\Actions;

use TruthQr\Publishing\TruthQrPublisherFactory;
use Lorisleiva\Actions\Concerns\AsAction;
use TruthCodec\Contracts\TransportCodec;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use TruthQr\Contracts\TruthQrWriter;
use Illuminate\Http\UploadedFile;
use App\Models\ElectionReturn;
use Illuminate\Support\Str;

/**
 * GenerateQrForJson
 * -----------------
 * Builds QR **chunk texts** (and optionally PNG images) for a JSON payload representing
 * an Election Return (ER). Each chunk is an envelope line:
 *
 *   ER|v1|<CODE>|<INDEX>/<TOTAL>|<PAYLOAD>
 *
 * where `<PAYLOAD> = base64url( gzdeflate(JSON, 9) )`.
 *
 * ## Features
 * - Accepts JSON as array, string, or uploaded file.
 * - Computes chunks either by fixed character budget (max chars per QR) or single-QR mode.
 * - Optionally produces PNG data URIs per chunk (scan-ready).
 * - Optionally persists a full export folder: manifest.json, raw.json, README.md,
 *   chunk_XofN.txt, and chunk_XofN.png.
 * - GET endpoint: `/api/qr/election-return/{code}` with several tuning query params.
 * - POST endpoint: `/api/qr/election-return` to generate directly from a JSON body.
 *
 * ## Indexing
 * - `<INDEX>` and `<TOTAL>` in the envelope are **1-based**.
 * - The array of returned chunks is **0-based** in PHP, but each chunk includes a
 *   1-based `index` key for clarity.
 *
 * ## Knobs & Tuning
 * - `desired_chunks`: target number of QR codes (we compute an effective per-QR char budget).
 * - `max_chars_per_qr`: explicit char budget after deflate+base64url (default 1200 via controller).
 * - `single`: force a single QR (may be too dense for large payloads).
 * - `make_images`: include PNG data URIs for each chunk.
 * - `ecc`, `size`, `margin`: QR appearance & readability parameters.
 *
 * ## Persistence
 * When enabled, outputs are written under:
 *   `storage/app/qr_exports/{CODE}/{persist_dir or Ymd_His}/`
 *
 * Contents:
 * - manifest.json  (the method’s full result payload)
 * - raw.json       (original JSON, pretty printed)
 * - README.md      (how to reassemble/verify)
 * - chunk_XofN.txt (envelope text)
 * - chunk_XofN.png (QR image when enabled)
 *
 * ## Return shape
 * @return array{
 *   code: string,
 *   version: "v1",
 *   total: int,
 *   chunks: array<int, array{
 *     index: int,            // 1-based
 *     text: string,          // "ER|v1|<CODE>|<INDEX>/<TOTAL>|<PAYLOAD>"
 *     png?: string,          // data URI when makeImages=true
 *     png_error?: string     // present when image generation failed
 *   }>,
 *   params?: array{          // echoed by controllers for transparency
 *     payload?: string,
 *     effective_max_chars_per_qr: int,
 *     desired_chunks?: int|null,
 *     ecc: string,
 *     size: int,
 *     margin: int
 *   },
 *   persisted_to?: string    // absolute path to export folder when persisted
 * }
 */
class GenerateQrForJson
{
    use AsAction;

    /**
     * Build QR chunk texts (and optional PNGs) from an array|string|UploadedFile JSON source.
     *
     * Envelope format per chunk:
     *   ER|v1|<CODE>|<INDEX>/<TOTAL>|<PAYLOAD>
     * where <PAYLOAD> is base64url( gzdeflate(JSON, 9) ).
     *
     * @param  array<string,mixed>|string|\Illuminate\Http\UploadedFile $json
     *         JSON payload as an array, raw JSON string, or uploaded file (containing JSON).
     * @param  string $code
     *         Artifact code to embed in each envelope line.
     * @param  bool   $makeImages
     *         When true, include `png` data URIs in each chunk (default true via controllers).
     * @param  int    $maxCharsPerQr
     *         Maximum characters per chunk **after** deflate+base64url (ignored when `$forceSingle` true).
     * @param  bool   $forceSingle
     *         Force a single chunk/QR (payload might become too dense).
     * @param  array<string, mixed> $imageOptions
     *         Options for QR image generation (when `$makeImages` is true):
     *         - 'ecc'   : "low" | "medium" | "quartile" | "high" (default: "medium")
     *         - 'size'  : int pixels (default: 640)
     *         - 'margin': int pixels (default: 12)
     *
     * @return array{
     *   code: string,
     *   version: "v1",
     *   total: int,
     *   chunks: array<int, array{index:int,text:string,png?:string,png_error?:string}>
     * }
     */
    public function handle(
        array|string|UploadedFile $json,
        string $code,
        bool $makeImages = true,
        int $maxCharsPerQr = 3200,
        bool $forceSingle = false,
        array $imageOptions = [] // kept for signature compatibility; writer sizing is set via config
    ): array {
        // Normalize payload to array (the publisher works with arrays)
        if ($json instanceof UploadedFile) {
            $raw = (string) file_get_contents($json->getRealPath());
            $payload = json_decode($raw, true);
        } elseif (is_string($json)) {
            $payload = json_decode($json, true);
        } else {
            $payload = $json;
        }

        if (!is_array($payload)) {
            throw new \InvalidArgumentException('QR generator expects array or JSON for $json.');
        }

        /** @var TruthQrPublisherFactory $factory */
        $factory = app(TruthQrPublisherFactory::class);

        // Strategy: force single => ask for a huge size; otherwise use size=maxCharsPerQr
        $publishOptions = $forceSingle
            ? ['by' => 'size', 'size' => PHP_INT_MAX]
            : ['by' => 'size', 'size' => max(1, (int) $maxCharsPerQr)];

        // 1) Publish envelope lines via truth-qr-php (1-based keys)
        $lines = $factory->publish($payload, $code, $publishOptions);
        $n     = count($lines);

        // 2) Optionally publish QR images via the bound writer
        $images = [];
        if ($makeImages) {
            /** @var TruthQrWriter $writer */
            $writer = app(TruthQrWriter::class);
            // Factory returns binary images keyed 1..N
            $images = $factory->publishQrImages($payload, $code, $writer, $publishOptions);
        }

        // 3) Build result (keep chunk list 0-indexed, but text uses 1/N, as before)
        $chunks = [];
        $i = 0;
        foreach ($lines as $k => $text) { // $k is 1..N
            $chunk = [
                'index' => $i,   // keep 0-based index for backward compatibility with existing tests
                'text'  => $text,
            ];

            if ($makeImages && isset($images[$k]) && is_string($images[$k])) {
                // Convert binary to Data URI to preserve previous API contract
                // Guess by writer->format()
                $fmt = $writer->format(); // 'png' or 'svg' (we only ever included PNG before)
                if ($fmt === 'png') {
                    $chunk['png'] = 'data:image/png;base64,' . base64_encode($images[$k]);
                } elseif ($fmt === 'svg') {
                    // Keep the field name 'png' for contract compatibility when code expects it,
                    // or add both if you prefer:
                    $chunk['png'] = 'data:image/svg+xml;base64,' . base64_encode($images[$k]);
                } else {
                    // Fallback to generic
                    $chunk['png'] = 'data:application/octet-stream;base64,' . base64_encode($images[$k]);
                }
            }

            $chunks[] = $chunk;
            $i++;
        }

        return [
            'code'    => $code,
            'version' => 'v1',
            'total'   => $n,
            'chunks'  => $chunks,
        ];
    }

    /**
     * GET controller: produce QR chunks for an Election Return by its `{code}`.
     *
     * Route:
     *   GET /api/qr/election-return/{code}
     *
     * Query parameters:
     * - payload          : "full" (default) | "minimal"
     * - desired_chunks   : int (target QR count; computes effective chunk size)
     * - max_chars_per_qr : int (used when desired_chunks not provided; default 1200)
     * - single           : bool (force single QR; default false)
     * - make_images      : bool (include PNGs; default true)
     * - ecc              : "low"|"medium"|"quartile"|"high" (default "medium")
     * - size             : int (default 640)
     * - margin           : int (default 12)
     * - persist          : bool (write export bundle; default false)
     * - persist_dir      : string (optional folder name under the code directory)
     *
     * Response:
     *   JSON version of `handle()` result + a `params` block (echoing effective settings)
     *   and `persisted_to` when persistence is enabled.
     *
     * @param  \Lorisleiva\Actions\ActionRequest $request
     * @param  string                            $code
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 404 when ER not found.
     */
    public function asController(ActionRequest $request, string $code)
    {
        $er = ElectionReturn::with('precinct')->where('code', $code)->firstOrFail();

        /** @var \App\Data\ElectionReturnData $dto */
        $dto = $er->getData();

        // Build payload: minimal or full
        $payloadMode = strtolower((string) $request->input('payload', 'full'));
        if ($payloadMode === 'minimal') {
            $json = $this->buildMinimalPayload($dto);
        } else {
            $json = json_decode($dto->toJson(), true);
        }

        $makeImages    = $request->boolean('make_images', true);
        $forceSingle   = $request->boolean('single', false);
        $desiredChunks = (int) $request->input('desired_chunks', 0);
        $maxCharsPer   = (int) $request->input('max_chars_per_qr', 1200);

        // When desired_chunks provided, estimate chunk size using the configured TransportCodec
        if ($desiredChunks > 0 && !$forceSingle) {
            /** @var TransportCodec $transport */
            $transport = app(TransportCodec::class);
            $raw       = json_encode($json, JSON_UNESCAPED_SLASHES);
            $packed    = $transport->encode($raw); // e.g., base64url+deflate or whatever is configured
            $len       = strlen($packed);
            $computed  = (int) ceil($len / max(1, $desiredChunks));
            // clamp to a practical scanning range (tunable)
            $maxCharsPer = max(600, min($computed, 2400));
        }

        $result = $this->handle(
            json:          $json,
            code:          $dto->code,
            makeImages:    $makeImages,
            maxCharsPerQr: $maxCharsPer,
            forceSingle:   $forceSingle,
            imageOptions:  [] // sizing handled by writer config; kept for signature stability
        );

        $result['params'] = [
            'payload'                    => $payloadMode,
            'effective_max_chars_per_qr' => $maxCharsPer,
            'desired_chunks'             => $desiredChunks ?: null,
            // size/margin now come from writer config; keep them echoed if you like by reading config here
        ];

        // Optional persistence (unchanged)
        $persist    = $request->boolean('persist', false);
        $persistDir = trim((string) $request->input('persist_dir', ''), "/ \t\n\r\0\x0B");
        if ($persist) {
            $base = 'qr_exports/'.$dto->code.'/'.($persistDir !== '' ? $persistDir : now()->format('Ymd_His'));
            $this->persistChunks($result, $base, $json);
            $result['persisted_to'] = Storage::disk('local')->path($base);
        }

        return response()->json($result);
    }

    /**
     * POST controller: build QR chunks directly from a JSON payload (no DB).
     *
     * Route: POST /api/qr/election-return
     *
     * Request body (JSON):
     * {
     *   "json": object|string,     // required: ER payload as object or JSON string
     *   "code"?: string,           // optional; defaults to json['code'] or random "ER-XXXXXX"
     *   "desired_chunks"?: int,
     *   "max_chars_per_qr"?: int,
     *   "single"?: bool,
     *   "make_images"?: bool,
     *   "ecc"?: "low"|"medium"|"quartile"|"high",
     *   "size"?: int,
     *   "margin"?: int,
     *   "persist"?: bool,
     *   "persist_dir"?: string
     * }
     *
     * Response:
     *   JSON version of `handle()` result + `params` and optional `persisted_to`.
     *
     * @param  \Lorisleiva\Actions\ActionRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 422 when body is invalid.
     */
    public function fromBody(ActionRequest $request)
    {
        $payload = $request->input('json');

        // Accept either an array or a JSON string
        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                abort(422, 'Invalid JSON provided in "json" field.');
            }
            $payload = $decoded;
        }

        if (!is_array($payload)) {
            abort(422, '"json" must be an object/array or a JSON string.');
        }

        $code = (string) ($request->input('code') ?? ($payload['code'] ?? ('ER-' . Str::upper(Str::random(6)))));

        // Knobs (mirror GET controller)
        $makeImages    = $request->boolean('make_images', true);
        $forceSingle   = $request->boolean('single', false);
        $desiredChunks = (int) $request->input('desired_chunks', 0);
        $maxCharsPer   = (int) $request->input('max_chars_per_qr', 1200);

        $eccStr = strtolower((string) $request->input('ecc', 'medium'));
        $size   = (int) $request->input('size', 640);
        $margin = (int) $request->input('margin', 12);

        if ($desiredChunks > 0 && ! $forceSingle) {
            $raw     = json_encode($payload, JSON_UNESCAPED_SLASHES);
            $deflate = gzdeflate($raw, 9);
            $b64u    = $this->b64urlEncode($deflate);
            $len     = strlen($b64u);
            $computed    = (int) ceil($len / max(1, $desiredChunks));
            $maxCharsPer = max(600, min($computed, 2400));
        }

        $result = $this->handle(
            json:          $payload,
            code:          $code,
            makeImages:    $makeImages,
            maxCharsPerQr: $maxCharsPer,
            forceSingle:   $forceSingle,
            imageOptions:  [
                'ecc'    => $eccStr,
                'size'   => $size,
                'margin' => $margin,
            ],
        );

        $result['params'] = [
            'payload'                    => 'direct', // informative
            'effective_max_chars_per_qr' => $maxCharsPer,
            'desired_chunks'             => $desiredChunks ?: null,
            'ecc'                        => $eccStr,
            'size'                       => $size,
            'margin'                     => $margin,
        ];

        // Optional persistence (same behavior as GET)
        $persist    = $request->boolean('persist', false);
        $persistDir = trim((string) $request->input('persist_dir', ''), "/ \t\n\r\0\x0B");
        if ($persist) {
            $base = 'qr_exports/'.$code.'/'.($persistDir !== '' ? $persistDir : now()->format('Ymd_His'));
            $this->persistChunks($result, $base, $payload);
            $result['persisted_to'] = Storage::disk('local')->path($base);
        }

        return response()->json($result);
    }

    private function b64urlEncode(string $bin): string
    {
        return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
    }

    // ------------------- Helpers (unchanged) -------------------

    private function buildMinimalPayload(\App\Data\ElectionReturnData $dto): array
    {
        // (same as your previous implementation)
        $talliesArr = is_array($dto->tallies) ? $dto->tallies : $dto->tallies->toArray();
        $tallies = array_map(
            fn ($t) => [
                'position_code'  => is_array($t) ? ($t['position_code'] ?? null) : $t->position_code,
                'candidate_code' => is_array($t) ? ($t['candidate_code'] ?? null) : $t->candidate_code,
                'candidate_name' => is_array($t) ? ($t['candidate_name'] ?? null) : $t->candidate_name,
                'count'          => is_array($t) ? ($t['count'] ?? null) : $t->count,
            ],
            $talliesArr
        );

        $precinct = [
            'id'   => $dto->precinct->id ?? null,
            'code' => $dto->precinct->code ?? null,
        ];

        foreach (['location_name', 'latitude', 'longitude'] as $k) {
            if (isset($dto->precinct->$k) && $dto->precinct->$k !== null) {
                $precinct[$k] = $dto->precinct->$k;
            } elseif (is_array($dto->precinct) && array_key_exists($k, $dto->precinct)) {
                $precinct[$k] = $dto->precinct[$k];
            }
        }

        $inspectors = null;
        if (isset($dto->precinct->electoral_inspectors)) {
            $src = is_array($dto->precinct->electoral_inspectors)
                ? $dto->precinct->electoral_inspectors
                : (method_exists($dto->precinct->electoral_inspectors, 'toArray')
                    ? $dto->precinct->electoral_inspectors->toArray()
                    : []);
            $inspectors = array_map(function ($i) {
                $get = fn($key) => is_array($i) ? ($i[$key] ?? null) : ($i->$key ?? null);
                return [
                    'id'   => $get('id'),
                    'name' => $get('name'),
                    'role' => $get('role'),
                ];
            }, $src);
        }
        if ($inspectors && count($inspectors)) {
            $precinct['electoral_inspectors'] = $inspectors;
        }

        $signatures = null;
        if (isset($dto->signatures)) {
            $src = is_array($dto->signatures)
                ? $dto->signatures
                : (method_exists($dto->signatures, 'toArray') ? $dto->signatures->toArray() : []);
            $signatures = array_map(function ($s) {
                $get = fn($key) => is_array($s) ? ($s[$key] ?? null) : ($s->$key ?? null);
                return array_filter([
                    'id'        => $get('id'),
                    'name'      => $get('name') ?? $get('signatory_name'),
                    'role'      => $get('role'),
                    'signed_at' => $get('signed_at'),
                ], fn($v) => $v !== null);
            }, $src);
        }

        $out = [
            'id'       => $dto->id,
            'code'     => $dto->code,
            'precinct' => $precinct,
            'tallies'  => $tallies,
        ];

        if ($signatures && count($signatures)) {
            $out['signatures'] = $signatures;
        }

        return $out;
    }

    private function persistChunks(array $result, string $baseDir, array $rawJson): void
    {
        $disk = Storage::disk('local');
        $disk->makeDirectory($baseDir);

        $disk->put($baseDir.'/manifest.json', json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $disk->put($baseDir.'/raw.json',      json_encode($rawJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $disk->put($baseDir.'/README.md',     $this->buildReadmeText($result));

        $total = (int) ($result['total'] ?? 0);
        foreach (($result['chunks'] ?? []) as $chunk) {
            $idx  = (int) ($chunk['index'] ?? 0);
            $text = (string) ($chunk['text'] ?? '');
            $disk->put("{$baseDir}/chunk_".($idx+1)."of{$total}.txt", $text);

            if (!empty($chunk['png']) && is_string($chunk['png'])) {
                $parts = explode(',', $chunk['png'], 2); // data:[mime];base64,<...>
                if (count($parts) === 2) {
                    $meta = $parts[0];
                    $b64  = $parts[1];
                    $ext  = str_contains($meta, 'image/png') ? 'png' : (str_contains($meta, 'svg') ? 'svg' : 'bin');
                    $disk->put("{$baseDir}/chunk_".($idx+1)."of{$total}.{$ext}", base64_decode($b64));
                }
            }
        }
    }

    private function buildReadmeText(array $result): string
    {
        $code  = (string) ($result['code'] ?? 'ER');
        $total = (int)    ($result['total'] ?? 0);

        return <<<TXT
QR Export for Election Return {$code}
===================================

This folder contains a complete export of QR payload chunks for the election return.

Files
-----
- manifest.json
  Full output from the QR generation (including chunk text and inline image data URIs).

- raw.json
  The original, uncompressed JSON payload (human-readable).

- chunk_Xof{$total}.txt
  The exact QR text for chunk X (format: ER|v1|<CODE>|<X>/{$total}|<PAYLOAD>).

- chunk_Xof{$total}.png/svg
  The QR image for chunk X (when make_images=1), scannable with standard readers.

Reassemble / Decode
-------------------
1) From *.txt files:
   a. Sort by X (1..{$total}) and concatenate each file's payload segment:
      The payload is the part after the 4th '|' in each line.
   b. Base64URL **decode** the concatenated payload.
   c. **Decode using your configured transport** (in this app: base64url+deflate → gzinflate).
   d. The result is the original JSON (matches raw.json).

2) From images:
   a. Scan each QR and extract the line, then follow the same steps as above.

TXT;
    }
}
