<?php

namespace App\Console\Pipes;

use Closure;
use Illuminate\Support\Facades\Storage;

class ExportErJson
{
    public function handle($ctx, Closure $next)
    {
        // Resolve disk (prefer 'election' if configured)
        $diskName = $ctx->disk
            ?? (config('filesystems.disks.election') ? 'election' : 'local');
        $disk = Storage::disk($diskName);

        // Resolve dir suffix
        $dirSuffix = $ctx->dirSuffix ?? 'final';

        // Extract ER code from object/array
        $er = $ctx->er;
        $erCode = is_object($er)
            ? ($er->code ?? null)
            : (is_array($er) ? ($er['code'] ?? null) : null);

        if (!$erCode) {
            throw new \RuntimeException('ExportErJson: missing er.code');
        }

        // Derive folder if not provided on context
        $folder = $ctx->folder ?? ('ER-' . $erCode . '/' . $dirSuffix);
        $disk->makeDirectory($folder);

        // -------- raw.full.json --------
        $fullJson = method_exists($er, 'toJson')
            ? $er->toJson(JSON_PRETTY_PRINT)
            : json_encode($er, JSON_PRETTY_PRINT);
        $disk->put($folder . '/raw.full.json', $fullJson);

        // -------- raw.min.json --------
        // precinct id/code tolerant to array/object
        $precinct = is_object($er) ? ($er->precinct ?? null) : ($er['precinct'] ?? null);
        $precinctId = is_array($precinct) ? ($precinct['id'] ?? null) : ($precinct->id ?? null);
        $precinctCode = is_array($precinct) ? ($precinct['code'] ?? null) : ($precinct->code ?? null);

        // tallies can be array, Collection, DataCollection, or objects
        $talliesRaw = is_object($er) ? ($er->tallies ?? []) : ($er['tallies'] ?? []);
        if ($talliesRaw instanceof \Spatie\LaravelData\DataCollection) {
            $talliesRaw = $talliesRaw->toArray();
        } elseif ($talliesRaw instanceof \Illuminate\Support\Collection) {
            $talliesRaw = $talliesRaw->all();
        }

        $tallies = array_values(array_map(function ($t) {
            if ($t instanceof \Illuminate\Support\Collection) {
                $t = $t->toArray();
            } elseif (is_object($t) && method_exists($t, 'toArray')) {
                $t = $t->toArray();
            }
            return [
                'position_code'  => is_array($t) ? ($t['position_code'] ?? null) : ($t->position_code ?? null),
                'candidate_code' => is_array($t) ? ($t['candidate_code'] ?? null) : ($t->candidate_code ?? null),
                'candidate_name' => is_array($t) ? ($t['candidate_name'] ?? null) : ($t->candidate_name ?? null),
                'count'          => (int) (is_array($t) ? ($t['count'] ?? 0) : ($t->count ?? 0)),
            ];
        }, is_array($talliesRaw) ? $talliesRaw : []));

        $minimal = [
            'id'       => is_object($er) ? ($er->id ?? null) : ($er['id'] ?? null),
            'code'     => $erCode,
            'precinct' => ['id' => $precinctId, 'code' => $precinctCode],
            'tallies'  => $tallies,
        ];

        $disk->put($folder . '/raw.min.json', json_encode($minimal, JSON_PRETTY_PRINT));

        // Bubble up resolved values so later pipes can reuse
        $ctx->disk = $diskName;
        $ctx->folder = $folder;

        return $next($ctx);
    }
}
