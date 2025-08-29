<?php

namespace TruthQrUi;

use Illuminate\Support\ServiceProvider;

final class TruthQrUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Config publish
        $this->publishes([
            __DIR__ . '/../config/truth-qr-ui.php' => config_path('truth-qr-ui.php'),
        ], 'truth-qr-ui-config');

        // Inertia/Vue stubs publish
        $this->publishes([
            __DIR__ . '/../stubs/inertia/Pages/TruthQrUi/Playground.vue'
            => resource_path('js/Pages/TruthQrUi/Playground.vue'),
            __DIR__ . '/../stubs/inertia/components/TruthQrForm.vue'
            => resource_path('js/Pages/TruthQrUi/components/TruthQrForm.vue'),
            __DIR__ . '/../stubs/inertia/composables/useTruthQr.ts'
            => resource_path('js/Pages/TruthQrUi/composables/useTruthQr.ts'),
        ], 'truth-qr-ui-stubs');

        // Optional: load routes if you decide to ship default routes
         $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Optional: only register command in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                \TruthQrUi\Console\InstallCommand::class,
            ]);
        }
    }
}
