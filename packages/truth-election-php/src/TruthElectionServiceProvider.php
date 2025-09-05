<?php

namespace TruthElection;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

final class TruthElectionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/truth-election.php', 'truth-election');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/truth-election.php' => config_path('truth-election.php'),
        ], 'truth-election-config');

        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::prefix('api/truth-election')
            ->middleware(config('truth-election.middleware', ['api']))
            ->group(__DIR__.'/../routes/api.php');
    }
}
