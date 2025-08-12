<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;

class DecodeQrChunks
{
    use AsAction;

    /**
     * Decode a set of QR chunk texts into the original JSON.
     *
     * Input: array of lines like "ER|v1|<CODE>|<i>/<N>|<PAYLOAD>"
     *
     * @param  string[]  $chunks
     * @param  bool      $persist  When true, writes decoded files to storage/app/qr_decodes/<CODE>/<timestamp>/
     * @param  string|null $persistDir  Optional subdir under the code folder.
     * @return array{
     *   code: string,
     *   version: string,
     *   total: int,
     *   received_indices: int[],
     *   missing_indices: int[],
     *   json: array|null,
     *   raw_json?: string,
     *   persisted_to?: string
     * }
     */
    public function handle(array $chunks, bool $persist = false, ?string $persistDir = null): array
    {
        if (empty($chunks)) {
            abort(422, 'No chunks provided.');
        }

        $records = [];
        $code = null; $version = null; $total = null;

        foreach ($chunks as $line) {
            $line = trim((string)$line);
            if ($line === '') continue;

            // Format: ER|v1|<CODE>|<i>/<N>|<payload>
            $parts = explode('|', $line, 5);
            if (count($parts) < 5 || $parts[0] !== 'ER') {
                abort(422, 'Invalid chunk format.');
            }
            [$prefix, $ver, $c, $idxTot, $payload] = $parts;

            if (!preg_match('/^(\d+)\s*\/\s*(\d+)$/', $idxTot, $m)) {
                abort(422, 'Invalid index/total segment.');
            }
            $i = (int)$m[1]; $N = (int)$m[2];
            if ($i < 1 || $N < 1) abort(422, 'Index/total must be positive.');

            // Cross-check consistency
            $code    ??= $c;
            $version ??= $ver;
            $total   ??= $N;

            if ($c !== $code)    abort(422, "Mismatched code '{$c}' (expected '{$code}').");
            if ($ver !== $version) abort(422, "Mismatched version '{$ver}' (expected '{$version}').");
            if ($N !== $total)   abort(422, "Mismatched total {$N} (expected {$total}).");

            // keep the payload only (we’ll join later)
            $records[$i] = $payload;
        }

        // Compute missing
        $received = array_keys($records);
        sort($received);
        $missing = array_values(array_diff(range(1, (int)$total), $received));

        $decodedArray = null;
        $rawJson = null;

        // If all present, reconstruct
        if (empty($missing)) {
            $joined = '';
            for ($i = 1; $i <= $total; $i++) {
                $joined .= $records[$i] ?? '';
            }

            $bin = $this->b64urlDecode($joined);
            $inflated = @gzinflate($bin);
            if ($inflated === false) {
                abort(422, 'Failed to inflate payload (corrupt data?).');
            }

            $rawJson = $inflated;
            $decodedArray = json_decode($inflated, true);
            if (!is_array($decodedArray)) {
                abort(422, 'Decoded payload is not valid JSON.');
            }

            // Optional persistence
            if ($persist) {
                $this->persist($code, $version, $total, $chunks, $rawJson, $persistDir);
            }
        }

        $out = [
            'code'             => (string)$code,
            'version'          => (string)$version,
            'total'            => (int)$total,
            'received_indices' => $received,
            'missing_indices'  => $missing,
            'json'             => $decodedArray,
        ];

        if ($persist && $decodedArray !== null) {
            $out['raw_json'] = $rawJson; // include when persisted for transparency
            $out['persisted_to'] = $this->lastPersistedPath ?? null;
        }

        return $out;
    }

    public function asController(ActionRequest $request)
    {
        $chunks = $request->input('chunks');
        if (!is_array($chunks)) {
            abort(422, 'Request body must include "chunks": string[].');
        }

        $persist    = $request->boolean('persist', false);
        $persistDir = $request->input('persist_dir');

        return response()->json(
            $this->handle($chunks, $persist, $persistDir ? trim((string)$persistDir, "/") : null)
        );
    }

    // ---- helpers ----

    private ?string $lastPersistedPath = null;

    private function b64urlDecode(string $txt): string
    {
        $pad = strlen($txt) % 4;
        if ($pad) $txt .= str_repeat('=', 4 - $pad);
        return base64_decode(strtr($txt, '-_', '+/')) ?: '';
    }

    private function persist(string $code, string $version, int $total, array $lines, string $rawJson, ?string $persistDir): void
    {
        $disk = Storage::disk('local');
        $base = 'qr_decodes/'.$code.'/'.($persistDir ?: now()->format('Ymd_His'));
        $disk->makeDirectory($base);

        // Save the raw inputs
        $disk->put($base.'/chunks.txt', implode("\n", array_map('strval', $lines)));
        $disk->put($base.'/raw.json', $rawJson);

        // Minimal manifest
        $manifest = [
            'code'    => $code,
            'version' => $version,
            'total'   => $total,
            'saved_at'=> now()->toIso8601String(),
        ];
        $disk->put($base.'/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

        $this->lastPersistedPath = $disk->path($base);
    }
}
