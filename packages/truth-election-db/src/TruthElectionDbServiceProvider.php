<?php

namespace TruthElectionDb;

use TruthElectionDb\Console\Commands\RecordStatisticsCommand;
use TruthElectionDb\Console\Commands\SetupElectionCommand;
use TruthElectionDb\Console\Commands\AttestReturnCommand;
use TruthElectionDb\Console\Commands\WrapUpVotingCommand;
use TruthElectionDb\Console\Commands\CastBallotCommand;
use TruthElectionDb\Console\Commands\TallyVotesCommand;
use TruthElectionDb\Support\DatabaseElectionStore;
use TruthElection\Support\ElectionStoreInterface;
use Illuminate\Support\ServiceProvider;

class TruthElectionDbServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ElectionStoreInterface::class, DatabaseElectionStore::class);

        $this->mergeConfigFrom(__DIR__ . '/../config/truth-election-db.php', 'truth-election-db');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/truth-election-db.php' => config_path('truth-election-db.php'),
        ], 'truth-election-db-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../routes/web.php' => base_path('routes/web.php'),
        ], 'truth-election-db-routes');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupElectionCommand::class,
                CastBallotCommand::class,
                TallyVotesCommand::class,
                AttestReturnCommand::class,
                RecordStatisticsCommand::class,
                WrapUpVotingCommand::class
            ]);
        }
    }
}
