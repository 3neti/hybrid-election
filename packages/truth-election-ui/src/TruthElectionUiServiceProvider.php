<?php

namespace TruthElectionUi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TruthElectionUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRoutes();
        $this->publishesConfig();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/truth-election-ui.php', 'truth-election-ui');
    }

    protected function registerRoutes(): void
    {
        Route::middleware(['web'])
            ->prefix('truth-election')
            ->group(__DIR__ . '/../routes/web.php');
    }

    protected function publishesConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/truth-election-ui.php' => config_path('truth-election-ui.php'),
        ], 'truth-election-ui-config');
    }
}
