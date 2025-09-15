<?php

namespace TruthElectionDb\Console\Commands;

use TruthElectionDb\Actions\TallyVotes;
use Illuminate\Console\Command;

class TallyVotesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run with:
     *   php artisan election:tally P-001
     */
    protected $signature = 'election:tally
        {precinct_code : The code of the precinct to tally votes for}';

    /**
     * The console command description.
     */
    protected $description = 'Tally votes for a given precinct using the TallyVotes action.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $precinctCode = $this->argument('precinct_code');

        try {
            $result = TallyVotes::make()->run($precinctCode);

            $this->info('✅ Tally complete:');
            $this->line("Precinct: $precinctCode");

            $lastBallot = $result->toArray()['last_ballot'] ?? [];

            $this->line("🗳 Last Ballot Cast: {$lastBallot['code']}");
            $this->newLine();

            foreach ($lastBallot['votes'] ?? [] as $vote) {
                $position = $vote['position']['name'] ?? 'Unknown Position';
                $this->line("Position: $position");

                foreach ($vote['candidates'] ?? [] as $candidate) {
                    $name = $candidate['name'] ?? 'Unknown';
                    $votes = $candidate['votes'] ?? 1; // fallback to 1 if not explicitly tallied
                    $this->line("  - $name ({$votes} vote" . ($votes === 1 ? '' : 's') . ")");
                }

                $this->newLine();
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Error generating tally: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
