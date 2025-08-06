<?php

namespace App\Console\Commands;

use App\Actions\GenerateElectionReturn as GenerateElectionReturnAction;
use App\Data\VoteCountData;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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

        $return = GenerateElectionReturnAction::run(
            \App\Models\Precinct::with('ballots')->firstOrFail()
        );

        $precinct = $return->precinct;

        $totalBallots = \App\Models\Precinct::find($precinct->id)->ballots->count();
        $datetime = now()->toDayDateTimeString();

        // 🧾 Precinct Information Header
        $this->info("📌 Precinct Code     : {$precinct->code}");
        $this->info("📍 Location          : {$precinct->location_name}");
        $this->info("🕒 Generated At      : {$datetime}");
        $this->info("🧾 Total Ballots     : {$totalBallots}");
        $this->line('');

        // 👥 Electoral Board
        $this->info('👥 Electoral Board:');
        $this->line(' - Chairman: ' . ($precinct->chairman_name ?? 'N/A'));
        $this->line(' - Member 1: ' . ($precinct->member1_name ?? 'N/A'));
        $this->line(' - Member 2: ' . ($precinct->member2_name ?? 'N/A'));

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
