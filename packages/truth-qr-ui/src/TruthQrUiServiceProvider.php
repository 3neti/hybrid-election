<?php

namespace TruthQrUi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

final class TruthQrUiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/truth-qr-ui.php', 'truth-qr-ui');
    }

    public function boot(): void
    {
        // Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/ui.php');

        // Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'truth-qr-ui');

        // Publishable config & views
        $this->publishes([
            __DIR__.'/../config/truth-qr-ui.php' => config_path('truth-qr-ui.php'),
        ], 'truth-qr-ui-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/truth-qr-ui'),
        ], 'truth-qr-ui-views');
    }
}
