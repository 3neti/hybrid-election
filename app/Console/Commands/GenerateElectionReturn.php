<?php

namespace App\Console\Commands;

use App\Actions\GenerateElectionReturn as GenerateElectionReturnAction;
use App\Data\{ElectoralInspectorData, VoteCountData};
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Precinct;

/** @deprecated */
class GenerateElectionReturn extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:generate-election-return';

    /**
     * The console command description.
     */
    protected $description = 'Generates and displays a precinct-level election return summary';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->line('');
        $this->info('📥 Generating Election Return...');
        $this->line('');

        $precinct = Precinct::with('ballots')->firstOrFail();
        $return = GenerateElectionReturnAction::run($precinct);

        $this->info("🆔 Election Return ID: {$return->id}");
        $this->info("🔐 Return Code       : {$return->code}");
        $this->info("📌 Precinct Code     : {$return->precinct->code}");
        $this->info("📍 Location          : {$return->precinct->location_name}");
        $this->info("🕒 Generated At      : {$return->created_at->toDayDateTimeString()}");

        $totalBallots = $precinct->ballots->count();
        $this->info("🧾 Total Ballots     : {$totalBallots}");
        $this->line('');

        // 👥 Electoral Board
        $this->info('👥 Electoral Board:');
        $roles = ['chairperson' => 'Chairperson', 'member' => 'Member'];

        $groupedInspectors = collect($return->signatures)
            ->map(fn($inspector) => ElectoralInspectorData::from($inspector))
            ->groupBy(fn($inspector) => $inspector->role->value);

        foreach ($roles as $role => $label) {
            $inspectors = $groupedInspectors->get($role, collect());

            foreach ($inspectors as $index => $inspector) {
                $labelText = $role === 'member' ? "{$label} " . ($index + 1) : $label;
                $this->line(" - {$labelText}: {$inspector->name}");
            }
        }

        $this->line(str_repeat('-', 60));

        // 🗳️ Display Vote Tallies
        $grouped = collect($return->tallies)
            ->map(fn($vote) => VoteCountData::from($vote))
            ->groupBy('position_code');

        foreach ($grouped as $position => $candidates) {
            $this->info("🗳️  Position: {$position}");

            $sorted = $candidates->sortByDesc('count')->values();
            $maxRankWidth = strlen((string) $sorted->count());
            $maxNameLength = $sorted->map(fn($vote) => Str::length($vote->candidate_name))->max();
            $totalPadding = $maxRankWidth + 2 + $maxNameLength + 2;

            foreach ($sorted as $index => $vote) {
                $rank = $index + 1;
                $rankStr = str_pad("{$rank}.", $maxRankWidth + 2);
                $name = $vote->candidate_name;
                $namePadding = $totalPadding - Str::length($rankStr . $name);

                $this->line(
                    $rankStr . $name . str_repeat(' ', $namePadding) . str_pad($vote->count, 5, ' ', STR_PAD_LEFT)
                );
            }

            $this->info("🧮 Total Votes Cast for {$position}: " . $candidates->sum('count'));
            $this->line('');
        }

        $this->info('✅ Election return appreciation complete.');
    }
}
