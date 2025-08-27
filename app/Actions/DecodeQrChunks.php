<?php

namespace App\Actions;

use TruthQr\Assembly\Contracts\TruthAssemblerContract;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use TruthQr\Classify\Classify;

/**
 * DecodeQrChunks
 * --------------
 * Decodes a set of TRUTH QR chunk texts back into the original JSON payload.
 *
 * ## Chunk text format (envelope)
 * Each element in the `$chunks` array is a single line that looks like:
 *
 *    ER|v1|<CODE>|<INDEX>/<TOTAL>|<PAYLOAD>
 *
 * - Prefix:         literal "ER"
 * - Version:        "v1" (currently supported)
 * - CODE:           arbitrary string identifying the artifact (e.g., ER code)
 * - INDEX/TOTAL:    **1-based** position and total number of chunks (e.g. "2/5")
 * - PAYLOAD:        transport-encoded fragment (e.g., base64url(deflate(JSON)))
 *
 * ⚠ Indexing conventions:
 * - The envelope index `<INDEX>` is **1-based** and is the authoritative position.
 * - Any `index` fields that may appear in upstream generator responses are often **0-based**
 *   and are **not** trusted by this action; they are ignored here.
 *
 * ## Behavior
 * - Validates that all chunks share the same CODE, VERSION, and TOTAL.
 * - Ignores exact duplicate chunks for the same `<INDEX>`.
 * - Rejects duplicate chunks for the same `<INDEX>` when the payload differs.
 * - Computes `received_indices` and `missing_indices` using 1-based indexing.
 * - If all parts are present, joins the `<PAYLOAD>` fragments by ascending `<INDEX>`,
 *   decodes (base64url), inflates (raw DEFLATE), and JSON-decodes into an array.
 * - Optionally persists the inputs and decoded outputs under `storage/app/qr_decodes/{CODE}/{timestamp or persistDir}/`.
 *
 * ## Persistence
 * When `$persist = true`, the following are written:
 * - `chunks.txt`  : the input lines exactly as received (one per line).
 * - `raw.json`    : the decoded JSON string (pretty printed).
 * - `manifest.json`: minimal metadata (code, version, total, saved_at).
 *
 * ## Error handling
 * The method throws HTTP 422 (via `abort(422, ...)`) for:
 * - Empty input.
 * - Malformed line (missing parts or incorrect `INDEX/TOTAL` segment).
 * - Non-positive indices or `INDEX > TOTAL`.
 * - Mismatched CODE / VERSION / TOTAL across lines.
 * - Duplicate index with conflicting payload.
 * - Full set present but inflate or JSON decode fails.
 *
 * ## Return shape
 * @return array{
 *   code: string,
 *   version: string,
 *   total: int,
 *   received_indices: int[],  // 1-based indices that were provided
 *   missing_indices: int[],   // 1-based indices that are still missing
 *   json: array<string,mixed>|null, // decoded JSON when complete; otherwise null
 *   raw_json?: string,        // included when $persist === true and decoding succeeded
 *   persisted_to?: string     // absolute path to persisted folder when $persist === true and decoding succeeded
 * }
 */
class DecodeQrChunks
{
    use AsAction;

    public function __construct(
        private readonly TruthAssemblerContract $assembler
    ) {}

    /**
     * Decode a set of QR chunk texts into the original JSON.
     *
     * @param  string[]     $chunks      Array of lines, each in the form "ER|v1|<CODE>|<INDEX>/<TOTAL>|<PAYLOAD>"
     *                                   where `<INDEX>` is **1-based**.
     * @param  bool         $persist     When true, persist inputs and decoded outputs to `storage/app/qr_decodes/{CODE}/{subdir}/`.
     * @param  string|null  $persistDir  Optional subfolder name under the code directory; defaults to `Ymd_His` when omitted.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 422 on validation/decoding errors (see class doc).
     *
     * @return array{
     *   code: string,
     *   version: string,
     *   total: int,
     *   received_indices: int[],
     *   missing_indices: int[],
     *   json: array<string,mixed>|null,
     *   raw_json?: string,
     *   persisted_to?: string
     * }
     */
    public function handle(array $chunks, bool $persist = false, ?string $persistDir = null): array
    {
        if (empty($chunks)) {
            abort(422, 'No chunks provided.');
        }

        // 1) Normalize & validate all lines *before* ingesting into Classify
        $lines = array_values(array_filter(array_map(
            fn($s) => trim((string)$s),
            $chunks
        ), fn($s) => $s !== ''));

        if (!$lines) {
            abort(422, 'No chunks provided.');
        }

        [$code, $version, $total, $indexPayloadMap] = $this->validateAndCollect($lines);

        // 2) Compute received & missing indices from the *input* (stable for tests)
//        $received = array_keys($indexPayloadMap);
        $received = array_map('intval', array_keys($indexPayloadMap));
        sort($received);
//        $missing = array_values(array_diff(range(1, $total), $received));
        $missing = array_map(
            'intval',
            array_values(array_diff(range(1, (int) $total), $received))
        );

        $json = null;
        $rawJson = null;

        // 3) Ingest via Classify (store/assemble) — only assemble if complete
        $classify = new Classify($this->assembler);
        $sess     = $classify->newSession();

        try {
            $sess->addLines($lines);
        } catch (\Throwable $e) {
            // Normalize ingestion/parse errors into a 422 like before
            abort(422, $e->getMessage());
        }

        if (empty($missing)) {
            // Full set present -> assemble
            $json = $sess->assemble();

            // Prefer serialized artifact blob if available (keeps "raw_json" faithful)
            $art = $this->assembler->artifact($code);
            if (is_array($art) && isset($art['mime'], $art['body']) && $art['mime'] === 'application/json') {
                $rawJson = (string) $art['body'];
            } else {
                $rawJson = json_encode($json, JSON_UNESCAPED_SLASHES);
            }

            if ($persist) {
                $this->persist($code, $version, $total, $lines, $rawJson, $persistDir ? trim($persistDir, "/") : null);
            }
        }

        $out = [
            'code'             => $code,
            'version'          => $version,
            'total'            => $total,
            'received_indices' => $received,
            'missing_indices'  => $missing,
            'json'             => $json,
        ];

        if ($persist && $json !== null) {
            $out['raw_json']     = $rawJson;
            $out['persisted_to'] = $this->lastPersistedPath ?? null;
        }

        return $out;
    }


    /**
     * HTTP controller entrypoint.
     *
     * Request body (JSON):
     * {
     *   "chunks": string[],          // required; each element is "ER|v1|<CODE>|<INDEX>/<TOTAL>|<PAYLOAD>"
     *   "persist": boolean,          // optional; defaults to false
     *   "persist_dir": string|null   // optional; subfolder under the code directory
     * }
     *
     * Response: JSON-encoded version of the `handle()` return array.
     *
     * @param  \Lorisleiva\Actions\ActionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    // ----------------- helpers -----------------

    /** @var ?string $lastPersistedPath Absolute path of the last persistence, set when $persist === true and decode succeeded. */
    private ?string $lastPersistedPath = null;

    /**
     * Validates all lines for consistent code/version/total and index constraints,
     * tolerates exact duplicate index payloads, rejects conflicting duplicates.
     *
     * @param string[] $lines
     * @return array{0:string,1:string,2:int,3:array<int,string>} [code, version, total, index=>payload]
     */
    private function validateAndCollect(array $lines): array
    {
        $code = null;
        $version = null;
        $total = null;

        /** @var array<int,string> $indexPayloadMap */
        $indexPayloadMap = [];

        foreach ($lines as $line) {
            // Support ER|v1|CODE|i/N|payload  and  truth://v1/ER/CODE/i/N?c=payload
            if (str_starts_with($line, 'ER|')) {
                $parts = explode('|', $line, 5);
                if (count($parts) < 5 || $parts[0] !== 'ER') {
                    abort(422, 'Invalid chunk format.');
                }
                [$prefix, $ver, $c, $idxTot, $payload] = $parts;

                if (!preg_match('/^(\d+)\s*\/\s*(\d+)$/', $idxTot, $m)) {
                    abort(422, 'Invalid index/total segment.');
                }
                $i = (int)$m[1]; $N = (int)$m[2];
            } elseif (str_starts_with($line, 'truth://')) {
                // truth://v1/ER/<code>/<i>/<N>?c=<payload>
                $after = substr($line, strlen('truth://'));
                $qPos  = strpos($after, '?');
                $path  = $qPos === false ? $after : substr($after, 0, $qPos);
                $query = $qPos === false ? ''     : substr($after, $qPos + 1);

                $segs = array_values(array_filter(explode('/', $path), fn($s) => $s !== ''));
                // Expect: [v1, ER, <code>, <i>, <N>]
                if (count($segs) < 5 || strtoupper($segs[1]) !== 'ER') {
                    abort(422, 'Invalid truth:// chunk format.');
                }

                $ver = $segs[0];
                $c   = $segs[2];
                $i   = (int)$segs[3];
                $N   = (int)$segs[4];

                parse_str($query, $qs);
                $payload = (string)($qs['c'] ?? '');
                if ($payload === '') {
                    abort(422, 'Missing payload in truth:// chunk.');
                }
            } else {
                abort(422, 'Unrecognized chunk format.');
            }

            if ($i < 1 || $N < 1) {
                abort(422, 'Index/total must be positive.');
            }
            if ($i > $N) {
                abort(422, "Index {$i} cannot exceed total {$N}.");
            }

            // Cross-check consistency across lines
            $code    ??= $c;
            $version ??= $ver;
            $total   ??= $N;

            if ($c !== $code)      abort(422, "Mismatched code '{$c}' (expected '{$code}').");
            if ($ver !== $version) abort(422, "Mismatched version '{$ver}' (expected '{$version}').");
            if ($N !== $total)     abort(422, "Mismatched total {$N} (expected {$total}).");

            // Duplicate index handling: allow identical payload only
            if (array_key_exists($i, $indexPayloadMap)) {
                if ($indexPayloadMap[$i] !== $payload) {
                    abort(422, "Duplicate chunk #{$i} has conflicting payload.");
                }
                continue; // exact duplicate → ignore
            }

            $indexPayloadMap[$i] = $payload;
        }

        return [(string)$code, (string)$version, (int)$total, $indexPayloadMap];
    }

    private function persist(string $code, string $version, int $total, array $lines, string $rawJson, ?string $persistDir): void
    {
        $disk = Storage::disk('local');
        $base = 'qr_decodes/'.$code.'/'.($persistDir ?: now()->format('Ymd_His'));
        $disk->makeDirectory($base);

        $disk->put($base.'/chunks.txt', implode("\n", array_map('strval', $lines)));
        $disk->put($base.'/raw.json', $rawJson);

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
