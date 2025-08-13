<?php

namespace App\Actions;

use App\Models\ElectionReturn;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

/**
 * Generate QR code **chunks** for a JSON payload (Election Return).
 *
 * ## Chunk text format
 *   ER|v1|<CODE>|<INDEX>/<TOTAL>|<PAYLOAD>
 *
 * Where <PAYLOAD> is: Base64URL( gzdeflate(JSON) )
 *
 * ## Endpoint
 *   GET /api/qr/election-return/{code}
 *
 * ### Query Parameters
 * - payload (string, default: "full")
 * *     Which JSON shape to encode:
 * *       • "full"    → the entire DTO JSON (dto->toJson()) including last_ballot, signatures, etc.
 * *       • "minimal" → compact JSON:
 * *           {
 * *             id, code,
 * *             precinct: {
 * *               id, code, location_name?, latitude?, longitude?,
 * *               electoral_inspectors?: [{ id, name, role }]
 * *             },
 * *             tallies: [{ position_code, candidate_code, candidate_name, count }],
 * *             signatures?: [{ id?, name?, role?, signed_at? }] // metadata only, no image/blob
 * *           }
 * *     Use "minimal" to reduce the number of QR chunks while still showing precinct info.
 *
 * - desired_chunks (int, optional)
 *     Target number of QR codes (e.g., 4). If set (and single=0), the server computes a
 *     max_chars_per_qr to land near this count. Useful for tuning density.
 *
 * - max_chars_per_qr (int, default: 1200)
 *     Max characters per chunk **after** deflate+base64url. Used when desired_chunks is not set.
 *
 * - single (bool, default: false)
 *     Force a single QR (no chunking). ⚠ The payload may be too large for a single QR.
 *
 * - make_images (bool, default: true)
 *     Include PNG data URIs in the response for each chunk.
 *
 * - ecc (string, default: "medium")
 *     QR error correction level: low | medium | quartile | high.
 *
 * - size (int, default: 640)
 *     PNG size in pixels (square).
 *
 * - margin (int, default: 12)
 *     Quiet zone in pixels around the QR.
 *
 * - persist (bool, default: false)
 *     When true, persist **chunk text**, **PNG files**, a **manifest.json**, the original
 *     uncompressed **raw.json**, and a human‑readable **README.md** under:
 *       storage/app/qr_exports/{CODE}/{timestamp or persist_dir}/
 *
 * - persist_dir (string, optional)
 *     Optional subfolder name (e.g., "single_http", "multi_http"). If omitted, uses Ymd_His.
 *
 * ### Response
 * {
 *   code: string,
 *   version: "v1",
 *   total: number,
 *   chunks: [
 *     { index: number, text: string, png?: string (data URI), png_error?: string },
 *     ...
 *   ],
 *   params: { effective_max_chars_per_qr: number, desired_chunks?: number, ecc: string, size: number, margin: number },
 *   persisted_to?: string (absolute path when persist=1)
 * }
 */
class GenerateQrForJson
{
    use AsAction;

    /**
     * Build QR chunks from an array|string|UploadedFile JSON source.
     */
    public function handle(
        array|string|UploadedFile $json,
        string $code,
        bool $makeImages = true,
        int $maxCharsPerQr = 3200,
        bool $forceSingle = false,
        array $imageOptions = [] // ['ecc' => 'medium', 'size' => 640, 'margin' => 12]
    ): array {
        // 1) Normalize JSON
        if ($json instanceof UploadedFile) {
            $raw = file_get_contents($json->getRealPath());
        } elseif (is_array($json)) {
            $raw = json_encode($json, JSON_UNESCAPED_SLASHES);
        } else {
            $raw = (string) $json;
        }

        // 2) Compress + Base64URL
        $deflated = gzdeflate($raw, 9);
        $b64u     = $this->b64urlEncode($deflated);

        // 3) Chunk (or force single)
        $parts = $forceSingle ? [$b64u] : str_split($b64u, $maxCharsPerQr);
        $total = max(1, count($parts));

        $chunks = [];
        foreach ($parts as $i => $payload) {
            $index = $i + 1;
            $text  = $this->wrapChunk($payload, $index, $total, $code);

            $chunk = ['index' => $index, 'text' => $text];

            if ($makeImages) {
                try {
                    $chunk['png'] = $this->qrPngDataUri($text, $imageOptions);
                } catch (\Throwable $e) {
                    // Preserve text even if PNG generation fails (too dense, etc.)
                    $chunk['png_error'] = $e->getMessage();
                }
            }

            $chunks[] = $chunk;
        }

        return [
            'code'    => $code,
            'version' => 'v1',
            'total'   => $total,
            'chunks'  => $chunks,
        ];
    }

    /**
     * Controller: produce QR chunks for an Election Return by its {code}.
     *
     * Examples:
     *   GET /api/qr/election-return/ERTEST001?payload=minimal&desired_chunks=4&make_images=1&ecc=medium&size=640&margin=12
     *   GET /api/qr/election-return/ERTEST001?payload=full&single=1&persist=1&persist_dir=single_http
     */
    public function asController(ActionRequest $request, string $code)
    {
        $er = ElectionReturn::with('precinct')->where('code', $code)->first();
        if (! $er) {
            abort(404, 'Election return not found.');
        }


        // ✅ Serialize the persisted ER, do NOT rebuild it from precinct
        /** @var \App\Data\ElectionReturnData $dto */
        $dto = $er->getData();   // provided by WithData trait (Spatie Data)

//        // Build the DTO so `with()` (e.g., last_ballot) is consistently applied.
//        /** @var \App\Data\ElectionReturnData $dto */
//        $dto = app(\App\Actions\GenerateElectionReturn::class)->run($er->precinct);

        // Payload mode: full (default) or minimal
        $payloadMode = strtolower((string) $request->input('payload', 'full'));
        if ($payloadMode === 'minimal') {
            $json = $this->buildMinimalPayload($dto);
        } else {
            $json = json_decode($dto->toJson(), true);
        }

        // Knobs
        $makeImages    = $request->boolean('make_images', true);
        $forceSingle   = $request->boolean('single', false);
        $desiredChunks = (int) $request->input('desired_chunks', 0);
        $maxCharsPer   = (int) $request->input('max_chars_per_qr', 1200);

        // Image options
        $eccStr = strtolower((string) $request->input('ecc', 'medium'));
        $size   = (int) $request->input('size', 640);
        $margin = (int) $request->input('margin', 12);

        // If caller asked for N chunks, compute a chunk size to hit ≈N (unless single)
        if ($desiredChunks > 0 && ! $forceSingle) {
            $raw     = json_encode($json, JSON_UNESCAPED_SLASHES);
            $deflate = gzdeflate($raw, 9);
            $b64u    = $this->b64urlEncode($deflate);
            $len     = strlen($b64u);

            $computed     = (int) ceil($len / max(1, $desiredChunks));
            // Clamp for scan reliability (inkjet‑friendly)
            $maxCharsPer  = max(600, min($computed, 2400));
        }

        $result = $this->handle(
            json:          $json,
            code:          $dto->code,
            makeImages:    $makeImages,
            maxCharsPerQr: $maxCharsPer,
            forceSingle:   $forceSingle,
            imageOptions:  [
                'ecc'    => $eccStr,
                'size'   => $size,
                'margin' => $margin,
            ],
        );

        // Add echo of effective params for transparency
        $result['params'] = [
            'payload'                     => $payloadMode,
            'effective_max_chars_per_qr'  => $maxCharsPer,
            'desired_chunks'              => $desiredChunks ?: null,
            'ecc'                         => $eccStr,
            'size'                        => $size,
            'margin'                      => $margin,
        ];

        // Optional on-disk persistence: text + PNGs + manifest + raw.json + README
        $persist    = $request->boolean('persist', false);
        $persistDir = trim((string) $request->input('persist_dir', ''), "/ \t\n\r\0\x0B");
        if ($persist) {
            $base = 'qr_exports/'.$dto->code.'/'.($persistDir !== '' ? $persistDir : now()->format('Ymd_His'));
            $this->persistChunks($result, $base, $json);
            $result['persisted_to'] = Storage::disk('local')->path($base);
        }

        return response()->json($result);
    }

    // ------------------- Helpers -------------------

    /**
     * Minimal payload: keep essentials + precinct info (location + inspectors) and light signatures.
     * Aims to be small but “complete enough” for the UI.
     */
    private function buildMinimalPayload(\App\Data\ElectionReturnData $dto): array
    {
        // --- Tallies (unchanged: simple shape) ---
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

        // --- Precinct core + location + electoral inspectors (lightweight) ---
        $precinct = [
            'id'   => $dto->precinct->id ?? null,
            'code' => $dto->precinct->code ?? null,
        ];

        // Optional location fields if present on the DTO
        foreach (['location_name', 'latitude', 'longitude'] as $k) {
            if (isset($dto->precinct->$k) && $dto->precinct->$k !== null) {
                $precinct[$k] = $dto->precinct->$k;
            } elseif (is_array($dto->precinct) && array_key_exists($k, $dto->precinct)) {
                $precinct[$k] = $dto->precinct[$k];
            }
        }

        // Optional electoral inspectors, keeping only id/name/role
        $inspectors = null;
        if (isset($dto->precinct->electoral_inspectors)) {
            $src = is_array($dto->precinct->electoral_inspectors)
                ? $dto->precinct->electoral_inspectors
                : (method_exists($dto->precinct->electoral_inspectors, 'toArray')
                    ? $dto->precinct->electoral_inspectors->toArray()
                    : []);
            $inspectors = array_map(function ($i) {
                // unify array/object access
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

        // --- Optional lightweight signatures (metadata only; NO blobs/images) ---
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
                    // DO NOT include any image/blob fields here
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

    private function wrapChunk(string $payload, int $index, int $total, string $code): string
    {
        return sprintf('ER|v1|%s|%d/%d|%s', $code, $index, $total, $payload);
    }

    private function qrPngDataUri(string $contents, array $opts = []): string
    {
        $eccStr = strtolower((string)($opts['ecc'] ?? 'medium'));
        $ecc    = match ($eccStr) {
            'low'      => ErrorCorrectionLevel::Low,
            'quartile' => ErrorCorrectionLevel::Quartile,
            'high'     => ErrorCorrectionLevel::High,
            default    => ErrorCorrectionLevel::Medium,
        };

        $size   = (int) ($opts['size']   ?? 640);
        $margin = (int) ($opts['margin'] ?? 12);

        $qr = new QrCode(
            data: $contents,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: $ecc,
            size: $size,
            margin: $margin,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $writer = new PngWriter();
        return $writer->write($qr)->getDataUri();
    }

    private function b64urlEncode(string $bin): string
    {
        return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
    }

    public static function b64urlDecode(string $txt): string
    {
        $pad = strlen($txt) % 4;
        if ($pad) $txt .= str_repeat('=', 4 - $pad);
        return base64_decode(strtr($txt, '-_', '+/')) ?: '';
    }

    /**
     * Persist:
     *  - manifest.json (the API/handle() result)
     *  - raw.json      (the original, uncompressed JSON payload)
     *  - README.md     (how to reassemble/verify)
     *  - chunk_<i>of<N>.txt
     *  - chunk_<i>of<N>.png (if present)
     */
    private function persistChunks(array $result, string $baseDir, array $rawJson): void
    {
        $disk = Storage::disk('local');
        $disk->makeDirectory($baseDir);

        // 1) manifest.json
        $disk->put(
            $baseDir.'/manifest.json',
            json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        // 2) raw.json (pretty, uncompressed)
        $disk->put(
            $baseDir.'/raw.json',
            json_encode($rawJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        // 3) README.md
        $disk->put($baseDir.'/README.md', $this->buildReadmeText($result));

        // 4) chunk files
        $total = (int) ($result['total'] ?? 0);
        foreach (($result['chunks'] ?? []) as $chunk) {
            $idx  = (int) ($chunk['index'] ?? 0);
            $text = (string) ($chunk['text'] ?? '');

            $disk->put("{$baseDir}/chunk_{$idx}of{$total}.txt", $text);

            if (!empty($chunk['png']) && is_string($chunk['png'])) {
                $parts = explode(',', $chunk['png'], 2); // data:image/png;base64,<...>
                if (count($parts) === 2) {
                    $meta = $parts[0];
                    $b64  = $parts[1];
                    $ext  = str_contains($meta, 'image/png') ? 'png' : 'bin';
                    $disk->put("{$baseDir}/chunk_{$idx}of{$total}.{$ext}", base64_decode($b64));
                }
            }
        }
    }

    /**
     * Build README.md content placed in each export folder.
     */
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
  Full output from the QR generation (including chunk text and inline PNG data URIs).

- raw.json
  The original, uncompressed JSON payload (human‑readable).

- chunk_Xof{$total}.txt
  The exact QR text for chunk X (format: ER|v1|<CODE>|<X>/{$total}|<PAYLOAD>).

- chunk_Xof{$total}.png
  The QR image for chunk X (when make_images=1), scannable with standard readers.

Reassemble / Decode
-------------------
1) From *.txt files:
   a. Sort by X (1..{$total}) and concatenate each file's payload segment:
      The payload is the part after the 4th '|' in each line.
   b. Base64URL decode the concatenated payload.
   c. Inflate with raw DEFLATE (gzinflate).
   d. The result is the original JSON (matches raw.json).

2) From *.png files:
   a. Scan each QR (1..{$total}) and read the full text (same format as above).
   b. Extract the payload piece, then perform steps (1b)-(1d).

Format Reference
----------------
Chunk text format:
  ER|v1|<CODE>|<INDEX>/<TOTAL>|<PAYLOAD>

Where <PAYLOAD> is Base64URL( gzdeflate(JSON) ).

Notes
-----
- 'payload=minimal' greatly reduces the number of QR codes.
- 'desired_chunks=N' asks the server to compute a chunk size to land near N codes.
- Increasing 'max_chars_per_qr' generates fewer, denser QR codes. Lower it if scans fail.
- 'single=1' tries to generate a single QR; it may be too dense for large payloads.
- 'make_images=0' skips PNG generation to save bandwidth and storage.

TXT;
    }
}
