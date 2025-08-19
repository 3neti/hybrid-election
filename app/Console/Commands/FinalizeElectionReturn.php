<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Precinct;
use App\Actions\GenerateElectionReturn as GenerateElectionReturnAction;
use App\Actions\GenerateQrForJson;

class FinalizeElectionReturn extends Command
{
    protected $signature = 'close-er {--force : Close even without required signatures}
                                     {--persist=final : Subdir name for export (e.g., final)}';
    protected $description = 'Generate final Election Return, export artifacts, and close balloting';

    public function handle(): int
    {
        $precinct = Precinct::query()->firstOrFail();
        $meta = $precinct->meta ?? [];

        if (($meta['balloting_open'] ?? true) === false) {
            $this->warn('Balloting already closed. Nothing to do.');
            return 0;
        }

        // 1) Build ER
        $er = GenerateElectionReturnAction::run($precinct);

        // 2) Check signatures (example policy: chair + any member)
        $haveChair = false; $haveMember = false;
        foreach ((array)($er->signatures ?? []) as $s) {
            $role = is_array($s) ? ($s['role'] ?? null) : ($s->role ?? null);
            if ($role === 'chairperson') $haveChair = true;
            if ($role === 'member')      $haveMember = true;
        }
        $force = (bool) $this->option('force');
        if (!($haveChair && $haveMember) && !$force) {
            $this->error('Missing required signatures (need chair + member). Use --force to override.');
            return 1;
        }

        // 3) Export artifacts
        $dirSuffix = $this->option('persist') ?: 'final';
        $baseDir = 'er_exports/'.$er->code.'/'.$dirSuffix;
        Storage::disk('local')->put($baseDir.'/raw.full.json', $er->toJson(JSON_PRETTY_PRINT));

        // minimal JSON (same shape your QR minimal endpoint uses)
        $minimal = [
            'id'       => $er->id,
            'code'     => $er->code,
            'precinct' => ['id' => $er->precinct->id, 'code' => $er->precinct->code],
            'tallies'  => collect($er->tallies)->map(fn($t) => [
                'position_code'  => $t['position_code'] ?? $t->position_code,
                'candidate_code' => $t['candidate_code'] ?? $t->candidate_code,
                'candidate_name' => $t['candidate_name'] ?? $t->candidate_name,
                'count'          => $t['count'] ?? $t->count,
            ])->values()->all(),
        ];
        Storage::disk('local')->put($baseDir.'/raw.min.json', json_encode($minimal, JSON_PRETTY_PRINT));

        // 4) Generate QR chunks (PNG on) and persist like your QR export convention
        $qr = app(GenerateQrForJson::class)->run(
            json: $minimal,
            code: $er->code,
            makeImages: true,
            maxCharsPerQr: 1200,
            forceSingle: false,
            persist: true,
            persistDir: $dirSuffix // your QR action already writes manifest/raw/PNGs
        );

        // 5) Close balloting
        $meta['balloting_open'] = false;
        $meta['closed_at'] = now()->toIso8601String();
        $precinct->meta = $meta;
        $precinct->save();

        $this->info('✅ Final ER exported to: storage/app/'.$baseDir);
        $this->info('🔒 Balloting CLOSED.');
        if ($force && !($haveChair && $haveMember)) {
            $this->warn('Closed with --force (signature quorum not met).');
            return 2;
        }
        return 0;
    }
}
