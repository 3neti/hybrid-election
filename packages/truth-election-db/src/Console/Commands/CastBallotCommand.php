<?php

namespace TruthElectionDb\Console\Commands;

use TruthElectionDb\Actions\CastBallot;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class CastBallotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can now run it either:
     *  php artisan election:cast --json='{"ballot_code": "BAL001", ...}'
     *  php artisan election:cast --input=/path/to/ballot.json
     */
    protected $signature = 'election:cast
        {--input= : Path to the ballot JSON file}
        {--json= : Raw ballot JSON string}';

    /**
     * The console command description.
     */
    protected $description = 'Cast a ballot from JSON input (file or raw JSON) using the CastBallot action.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $jsonOption = $this->option('json');
        $inputFile = $this->option('input');

        $data = [];

        if ($jsonOption) {
            $data = json_decode($jsonOption, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('❌ Failed to parse JSON: ' . json_last_error_msg());
                return self::FAILURE;
            }
        } elseif ($inputFile && File::exists($inputFile)) {
            $data = json_decode(File::get($inputFile), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('❌ Failed to parse JSON file: ' . json_last_error_msg());
                return self::FAILURE;
            }
        } else {
            $this->error('❌ No valid input. Please provide --json or --input.');
            return self::FAILURE;
        }

        try {
            $ballot = CastBallot::make()->run(
                ballotCode: $data['ballot_code'] ?? null,
                precinctCode: $data['precinct_code'] ?? null,
                votes: collect($data['votes'] ?? [])
            );

            $this->info('✅ Ballot successfully cast:');
            $this->line("Ballot Code: {$ballot->code}");
            $this->line("Precinct: {$ballot->getPrecinctCode()}");
            $this->line("Votes: " . $ballot->votes->count());

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Error casting ballot: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
