<?php

namespace TruthElectionDb;

use TruthElectionDb\Console\Commands\SetupElectionCommand;
use TruthElectionDb\Console\Commands\CastBallotCommand;
use TruthElectionDb\Console\Commands\TallyVotesCommand;
use TruthElectionDb\Support\DatabaseElectionStore;
use TruthElection\Support\ElectionStoreInterface;
use Illuminate\Support\ServiceProvider;

class TruthElectionDbServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind the interface to the concrete database-backed implementation
        $this->app->bind(ElectionStoreInterface::class, DatabaseElectionStore::class);

        // Merge package config with host application config
        $this->mergeConfigFrom(__DIR__ . '/../config/truth-election-db.php', 'truth-election-db');
    }

    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/truth-election-db.php' => config_path('truth-election-db.php'),
        ], 'truth-election-db-config');

        // Publish routes
        $this->publishes([
            __DIR__ . '/../routes/web.php' => base_path('routes/web.php'),
        ], 'truth-election-db-routes');

        // Load package routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupElectionCommand::class,
                CastBallotCommand::class,
                TallyVotesCommand::class
            ]);
        }
    }
}
