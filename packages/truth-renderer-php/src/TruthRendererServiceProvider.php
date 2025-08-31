<?php

namespace TruthRenderer;

use TruthRenderer\Contracts\RendererInterface;
use TruthRenderer\Contracts\TemplateRegistryInterface;
use TruthRenderer\Template\TemplateRegistry;
use Illuminate\Support\ServiceProvider;

class TruthRendererServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // If you ship a config file, allow publishing:
        $configPath = __DIR__ . '/../config/truth-renderer.php';
        if (file_exists($configPath)) {
            $this->publishes([
                $configPath => config_path('truth-renderer.php'),
            ], 'truth-renderer-config');

            $this->mergeConfigFrom($configPath, 'truth-renderer');

            // publish a default templates folder, if desired
            $this->publishes([
                __DIR__ . '/../stubs/templates' => base_path('resources/templates/core'),
            ], 'truth-renderer-templates');
        }
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the interface to the concrete implementation
        $this->app->bind(RendererInterface::class, Renderer::class);

        // Optionally allow resolution via the container by class name as well:
        $this->app->singleton(Renderer::class, function () {
            return new Renderer();
        });

        // TemplateRegistry binding with a default templates directory
        $this->app->singleton(TemplateRegistryInterface::class, function ($app) {
            $paths = [
                // namespace => directory
                'core' => base_path('resources/templates/core'),
                // add more as needed
            ];
            return new TemplateRegistry($paths);
        });
    }
}
