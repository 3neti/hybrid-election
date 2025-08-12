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
 *     Which JSON shape to encode:
 *       • "full"    → the entire DTO JSON (dto->toJson()) including last_ballot, signatures, etc.
 *       • "minimal" → compact JSON: { id, code, precinct:{id,code}, tallies:[...] }
 *     Use "minimal" to reduce the number of QR chunks.
 *
 * - make_images (bool, default: true)
 *     Include PNG data URIs in the response for each chunk.
 *
 * - max_chars_per_qr (int, default: 1200)
 *     Max characters per chunk **after** deflate+base64url.
 *
 * - single (bool, default: false)
 *     Force a single QR (no chunking). ⚠ The payload may be too large for a single QR.
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
 *     { index: number, text: string, png?: string (data URI) },
 *     ...
 *   ],
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
        bool $forceSingle = false
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
                $chunk['png'] = $this->qrPngDataUri($text);
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
     *   GET /api/qr/election-return/ERTEST001?payload=minimal&make_images=1&max_chars_per_qr=1200
     *   GET /api/qr/election-return/ERTEST001?payload=full&single=1&persist=1&persist_dir=single_http
     */
    public function asController(ActionRequest $request, string $code)
    {
        $er = ElectionReturn::with('precinct')->where('code', $code)->first();
        if (! $er) {
            abort(404, 'Election return not found.');
        }

        // Build the DTO so `with()` (e.g., last_ballot) is consistently applied.
        /** @var \App\Data\ElectionReturnData $dto */
        $dto = app(\App\Actions\GenerateElectionReturn::class)->run($er->precinct);

        // Payload mode: full (default) or minimal
        $payloadMode = strtolower((string) $request->input('payload', 'full'));
        if ($payloadMode === 'minimal') {
            $json = $this->buildMinimalPayload($dto);
        } else {
            // full DTO JSON
            $json = json_decode($dto->toJson(), true);
        }

        $makeImages  = $request->boolean('make_images', true);
        $maxCharsPer = (int) $request->input('max_chars_per_qr', 1200);
        $forceSingle = $request->boolean('single', false);

        $persist    = $request->boolean('persist', false);
        $persistDir = trim((string) $request->input('persist_dir', ''), "/ \t\n\r\0\x0B");

        $result = $this->handle(
            json:          $json,
            code:          $dto->code,
            makeImages:    $makeImages,
            maxCharsPerQr: $maxCharsPer,
            forceSingle:   $forceSingle
        );

        // Optional on-disk persistence: text + PNGs + manifest + raw.json + README
        if ($persist) {
            $base = 'qr_exports/'.$dto->code.'/'.($persistDir !== '' ? $persistDir : now()->format('Ymd_His'));
            $this->persistChunks($result, $base, $json);
            $result['persisted_to'] = Storage::disk('local')->path($base);
        }

        return response()->json($result);
    }

    // ------------------- Helpers -------------------

    /**
     * Minimal payload: keep only essentials to shrink QR count.
     */
    private function buildMinimalPayload(\App\Data\ElectionReturnData $dto): array
    {
        // Tallies are already simple (position_code, candidate_code, candidate_name, count)
        $tallies = array_map(
            fn ($t) => [
                'position_code'  => $t['position_code'] ?? $t->position_code,
                'candidate_code' => $t['candidate_code'] ?? $t->candidate_code,
                'candidate_name' => $t['candidate_name'] ?? $t->candidate_name,
                'count'          => $t['count'] ?? $t->count,
            ],
            // support both array-y and DTO collection cases
            is_array($dto->tallies) ? $dto->tallies : $dto->tallies->toArray()
        );

        return [
            'id'       => $dto->id,
            'code'     => $dto->code,
            'precinct' => [
                'id'   => $dto->precinct->id,
                'code' => $dto->precinct->code,
            ],
            'tallies'  => $tallies,
        ];
    }

    private function wrapChunk(string $payload, int $index, int $total, string $code): string
    {
        return sprintf('ER|v1|%s|%d/%d|%s', $code, $index, $total, $payload);
    }

    private function qrPngDataUri(string $contents): string
    {
        $qr = new QrCode(
            data: $contents,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 512,
            margin: 8,
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
- Increasing 'max_chars_per_qr' generates fewer, denser QR codes. Lower it if scans fail.
- 'single=1' tries to generate a single QR; it may be too dense for large payloads.
- 'make_images=0' skips PNG generation to save bandwidth and storage.

TXT;
    }
}
