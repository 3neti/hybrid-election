<?php

namespace TruthElectionDb\Console\Commands;

use TruthElection\Actions\InitializeSystem;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class SetupElectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     *  php artisan election:setup --election=/path/to/election.json --precinct=/path/to/precinct.yaml [--migrate]
     */
    protected $signature = 'election:setup
        {--election= : Path to the election.json file}
        {--precinct= : Path to the precinct.yaml file}
        {--migrate : Run migrations before setting up}';

    /**
     * The console command description.
     */
    protected $description = 'Set up election data using files and persist to database via truth-election-db';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Optionally run `php artisan migrate`
        if ($this->option('migrate')) {
            $this->info('📦 Running migrations...');
            $this->call('migrate', ['--force' => true]);
        }

        try {
            $result = InitializeSystem::run(
                electionPath: $this->option('election'),
                precinctPath: $this->option('precinct'),
            );
        } catch (QueryException $e) {
            $this->error("❌ Database error: {$e->getMessage()}");
            $this->line('');
            $this->warn("💡 Have you run `php artisan migrate`?");
            $this->line("👉 You can also run:");
            $this->line("   php artisan election:setup --election=... --precinct=... --migrate");
            return self::FAILURE;
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            $this->line('');
            $this->line('💡 You may also provide file paths explicitly:');
            $this->line('   php artisan election:setup --election=... --precinct=...');
            return self::FAILURE;
        }

        $this->info('✅ Election setup complete.');

        $this->table(
            ['Precinct Code', 'Positions Created', 'Candidates Created'],
            [[
                $result['summary']['precinct_code'] ?? '—',
                $result['summary']['positions']['created'] ?? 0,
                $result['summary']['candidates']['created'] ?? 0,
            ]]
        );

        return self::SUCCESS;
    }
}
