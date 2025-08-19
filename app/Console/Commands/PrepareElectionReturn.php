<?php

namespace App\Console\Commands;

use App\Actions\GenerateElectionReturn as GenerateElectionReturnAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Data\{ElectoralInspectorData, VoteCountData};
use App\Models\{Position, Precinct};
use App\Actions\InitializeSystem;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PrepareElectionReturn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage examples:
     *
     *  # Simple: initialize if needed, pick the first precinct, pretty-print to console
     *  php artisan prepare-er
     *
     *  # Choose a specific precinct by code
     *  php artisan prepare-er --precinct=CURRIMAO-001
     *
     *  # Emit raw JSON (for piping into other tools)
     *  php artisan prepare-er --json
     *
     *  # Force an initialization pass if system looks empty (non-destructive)
     *  php artisan prepare-er --init
     */
    protected $signature = 'prepare-er
        {--precinct= : Precinct code to summarize (defaults to the first precinct).}
        {--json : Output the Election Return as raw JSON instead of a formatted table.}
        {--init : If the system looks empty, initialize once from default config (non-destructive).}';

    /**
     * The console command description.
     */
    protected $description = 'Generates and displays (or prints JSON of) a precinct-level Election Return summary.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // ── Optional one-time initialization (parity with CastBallot local flow)
        if ($this->option('init')) {
            if (!Position::query()->exists() || !Precinct::query()->exists()) {
                $this->warn('System appears uninitialized. Initializing once from defaults...');
                InitializeSystem::run(reset: false); // non-destructive bootstrap
            }
        }

        // Find precinct (either by code or first one)
        $precinctCode = $this->option('precinct');
        $query = Precinct::with('ballots');

        /** @var Precinct $precinct */
//        $precinct = $precinctCode
//            ? $query->where('code', $precinctCode)->firstOrFail()
//            : $query->firstOrFail();

        try {
            $precinct = $precinctCode
                ? $query->where('code', $precinctCode)->firstOrFail()
                : $query->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // <-- this is what your test expects to see
            $this->error($e->getMessage());
            return self::FAILURE; // exit code 1
        }

        if ($precinct->ballots->isEmpty()) {
            $this->warn('No ballots found for this precinct yet. Nothing to summarize.');
            return self::SUCCESS;
        }

        // Build ER DTO
        $er = GenerateElectionReturnAction::run($precinct);

        // Raw JSON mode (integration-friendly)
        if ($this->option('json')) {
            // Spatie Data DTOs serialize cleanly via toJson()
            $this->line($er->toJson());
            return self::SUCCESS;
        }

        // Pretty console output
        $this->line('');
        $this->info('📥 Preparing Election Return…');
        $this->line('');

        $createdAt = $er->created_at;
        $createdStr = \is_object($createdAt) && \method_exists($createdAt, 'toDayDateTimeString')
            ? $createdAt->toDayDateTimeString()
            : (string) $createdAt;

        $this->info("🆔 Election Return ID: {$er->id}");
        $this->info("🔐 Return Code       : {$er->code}");
        $this->info("📌 Precinct Code     : {$er->precinct->code}");
        $this->info("📍 Location          : {$er->precinct->location_name}");
        $this->info("🕒 Generated At      : {$createdStr}");

        $totalBallots = $precinct->ballots->count();
        $this->info("🧾 Total Ballots     : {$totalBallots}");
        $this->line('');

        // 👥 Electoral Board (same role labeling as before)
        $this->info('👥 Electoral Board:');
        $roles = ['chairperson' => 'Chairperson', 'member' => 'Member'];

        $groupedInspectors = collect($er->signatures ?? [])
            ->map(fn ($i) => ElectoralInspectorData::from($i))
            ->groupBy(fn ($i) => $i->role->value);

        foreach ($roles as $role => $label) {
            $inspectors = $groupedInspectors->get($role, collect());
            foreach ($inspectors as $index => $inspector) {
                $labelText = $role === 'member' ? "{$label} " . ($index + 1) : $label;
                $this->line(" - {$labelText}: {$inspector->name}");
            }
        }

        $this->line(str_repeat('-', 60));

        // 🗳️ Vote Tallies (group → sort → print)
        $groupedTallies = collect($er->tallies ?? [])
            ->map(fn ($t) => VoteCountData::from($t))
            ->groupBy('position_code');

        foreach ($groupedTallies as $position => $candidates) {
            $this->info("🗳️  Position: {$position}");

            $sorted = $candidates->sortByDesc('count')->values();
            $maxRankWidth = strlen((string) $sorted->count());
            $maxNameLength = $sorted->map(fn ($vote) => Str::length($vote->candidate_name))->max() ?? 0;
            $totalPadding = $maxRankWidth + 2 + $maxNameLength + 2;

            foreach ($sorted as $index => $vote) {
                $rank = $index + 1;
                $rankStr = str_pad("{$rank}.", $maxRankWidth + 2);
                $name = $vote->candidate_name;
                $namePadding = max(0, $totalPadding - Str::length($rankStr . $name));

                $this->line(
                    $rankStr . $name . str_repeat(' ', $namePadding) . str_pad($vote->count, 5, ' ', STR_PAD_LEFT)
                );
            }

            $this->info("🧮 Total Votes Cast for {$position}: " . $candidates->sum('count'));
            $this->line('');
        }

        $this->info('✅ Election return preparation complete.');
        return self::SUCCESS;
    }
}
