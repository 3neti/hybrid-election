<?php

namespace App\Console\Commands;

use App\Actions\GenerateElectionReturn as GenerateElectionReturnAction;
use Illuminate\Support\Facades\{Http, Storage};
use Illuminate\Console\Command;
use App\Models\Precinct;

class FinalizeElectionReturn extends Command
{
    /**
     * Close the precinct’s election return:
     *  1) Generate final ER (server-side)
     *  2) Validate signatures (chair + at least one member unless --force)
     *  3) Export artifacts to storage:
     *       - raw.full.json
     *       - raw.min.json
     *       - QR chunks & PNGs (via existing HTTP QR route with persist=1)
     *  4) Mark balloting closed on the precinct
     */
    protected $signature = 'close-er
        {--force : Close even without required signatures (chair + member)}
        {--persist=final : Subdir name for export (e.g., final)}
        {--payload=minimal : Payload for QR export (minimal|full)}
        {--max-chars-per-qr=1200 : Target text length per QR chunk}
        ';

    protected $description = 'Generate final Election Return, export artifacts (JSON & QR), and close balloting';

    public function handle(): int
    {
        // 0) Resolve precinct and check current status
        /** @var \App\Models\Precinct $precinct */
        $precinct = Precinct::query()->firstOrFail();
        $meta = (array) ($precinct->meta ?? []);

        if (($meta['balloting_open'] ?? true) === false) {
            $this->warn('Balloting already closed. Nothing to do.');
            return self::SUCCESS;
        }

        // 1) Build latest ER (non-destructive; your action recomputes tallies)
        $er = GenerateElectionReturnAction::run($precinct);
        $this->info("ER code: {$er->code}");

        // 2) Check signatures (chair + at least one member) unless --force
        $signatures = $er->signatures ?? [];

// Normalize DataCollection / Collection / DTOs -> plain arrays
        if ($signatures instanceof \Spatie\LaravelData\DataCollection) {
            $signatures = $signatures->toArray();
        } elseif ($signatures instanceof \Illuminate\Support\Collection) {
            $signatures = $signatures->toArray();
        }

        $haveChair   = false;
        $memberCount = 0;

        foreach ((array) $signatures as $s) {
            // Each $s might be: array, DTO (with toArray), or a Collection
            if ($s instanceof \Illuminate\Support\Collection) {
                $s = $s->toArray();
            } elseif (is_object($s) && method_exists($s, 'toArray')) {
                $s = $s->toArray();
            }

            // At this point prefer array access, then fall back to object/enum
            $role = null;
            if (is_array($s)) {
                $role = $s['role'] ?? null;
                // If role is an enum-like object with ->value, unwrap it
                if (is_object($role) && property_exists($role, 'value')) {
                    $role = $role->value;
                }
            } elseif (is_object($s)) {
                // Last-resort object read
                $role = property_exists($s, 'role') ? $s->role : null;
                if (is_object($role) && property_exists($role, 'value')) {
                    $role = $role->value;
                }
            }

            if ($role === 'chairperson') {
                $haveChair = true;
            }
            if ($role === 'member') {
                $memberCount++;
            }
        }
//        $haveChair = false;
//        $memberCount = 0;
//        foreach ((array) ($er->signatures ?? []) as $s) {
//            // tolerate array or DTO/object
//            $role = is_array($s) ? ($s['role'] ?? null) : ($s->role ?? null);
//            if ($role === 'chairperson') {
//                $haveChair = true;
//            }
//            if ($role === 'member') {
//                $memberCount++;
//            }
//        }

        $force = (bool) $this->option('force');
        if (!($haveChair && $memberCount >= 1) && !$force) {
            $this->error('Missing required signatures (need chair + at least one member). Use --force to override.');
            return self::FAILURE;
        }

        // 3) Export artifacts to storage
        $dirSuffix = (string) ($this->option('persist') ?: 'final');
        $payload   = in_array(($this->option('payload') ?? 'minimal'), ['minimal', 'full'], true)
            ? (string) $this->option('payload')
            : 'minimal';
        $maxChars  = (int) ($this->option('max-chars-per-qr') ?? 1200);

        // Prefer a dedicated "election" disk if configured; otherwise fallback to local.
        $diskName = Storage::disk('election') ? 'election' : 'local';

        // Folder: election/ER-<code>/<dirSuffix>
        $folder = 'ER-' . $er->code . '/' . $dirSuffix;
        Storage::disk($diskName)->makeDirectory($folder);

        // 3a) Full JSON snapshot (the DTO supports toJson())
        Storage::disk($diskName)->put($folder . '/raw.full.json', $er->toJson(JSON_PRETTY_PRINT));

        // 3b) Minimal JSON (same shape your QR minimal route uses)
        $minimal = [
            'id'       => $er->id,
            'code'     => $er->code,
            'precinct' => [
                'id'   => $er->precinct->id,
                'code' => $er->precinct->code,
            ],
            'tallies'  => collect($er->tallies)->map(function ($t) {
                // tolerate array or DTO
                return [
                    'position_code'  => is_array($t) ? ($t['position_code'] ?? null) : $t->position_code,
                    'candidate_code' => is_array($t) ? ($t['candidate_code'] ?? null) : $t->candidate_code,
                    'candidate_name' => is_array($t) ? ($t['candidate_name'] ?? null) : $t->candidate_name,
                    'count'          => is_array($t) ? ($t['count'] ?? null) : $t->count,
                ];
            })->values()->all(),
        ];
        Storage::disk($diskName)->put($folder . '/raw.min.json', json_encode($minimal, JSON_PRETTY_PRINT));

        // 3c) Ask the existing QR endpoint to persist chunks & PNGs
        // This mirrors your already-tested QR export flow.
        try {
            $qrUrl = route('qr.er', ['code' => $er->code]) . '?' . http_build_query([
                    'payload'          => $payload, // minimal|full
                    'make_images'      => 1,        // include PNGs
                    'max_chars_per_qr' => $maxChars,
                    'persist'          => 1,        // write manifest/raw/PNGs server-side
                    'persist_dir'      => $dirSuffix,
                ]);

            $this->line('Requesting QR export: ' . $qrUrl);
            $resp = Http::acceptJson()->get($qrUrl);

            if (!$resp->ok()) {
                $this->error('QR export failed: ' . $resp->body());
                return self::FAILURE;
            }

            $qrJson = $resp->json();
            $this->info('QR export OK: total=' . ($qrJson['total'] ?? '?')
                . ', persisted=' . ($qrJson['persisted_to'] ?? '(n/a)'));
        } catch (\Throwable $e) {
            $this->error('QR export error: ' . $e->getMessage());
            return self::FAILURE;
        }

        // 4) Close balloting
        $meta['balloting_open'] = false;
        $meta['closed_at']      = now()->toIso8601String();
        $precinct->meta         = $meta;
        $precinct->save();

        $this->info('✅ Final ER exported under: storage/app/' . ($diskName === 'local' ? '' : ($diskName . '/')) . $folder);
        $this->info('🔒 Balloting CLOSED.');

        if ($force && !($haveChair && $memberCount >= 1)) {
            $this->warn('Closed with --force (signature quorum not met).');
            return 2; // distinguish "forced close" if you like
        }

        return self::SUCCESS;
    }
}
