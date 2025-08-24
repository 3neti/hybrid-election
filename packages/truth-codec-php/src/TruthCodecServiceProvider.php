<?php

namespace TruthCodec;

use TruthCodec\Serializer\AutoDetectSerializer;
use TruthCodec\Serializer\SerializerRegistry;
use TruthCodec\Contracts\PayloadSerializer;
use TruthCodec\Serializer\JsonSerializer;
use TruthCodec\Serializer\YamlSerializer;
use Illuminate\Support\ServiceProvider;

/**
 * Laravel service provider for TruthCodec.
 *
 * Responsibilities:
 * - Registers concrete serializers (JSON, YAML).
 * - Exposes a SerializerRegistry keyed by short names (e.g., "json", "yaml").
 * - Binds PayloadSerializer to an AutoDetectSerializer that tries formats in a
 *   configurable order and uses a configurable "primary" for deterministic encoding.
 * - Publishes a config file to allow per-app customization.
 */
class TruthCodecServiceProvider extends ServiceProvider
{
    /**
     * Register container bindings and package configuration.
     *
     * Bindings:
     * - JsonSerializer, YamlSerializer as singletons.
     * - SerializerRegistry as a singleton, pre-populated with "json" and "yaml".
     * - PayloadSerializer -> AutoDetectSerializer(JSON, YAML) by default,
     *   with order and primary pulled from config('truth-codec.*').
     */
    public function register(): void
    {
        // Merge default config so config('truth-codec.*') works without publish.
        $this->mergeConfigFrom(__DIR__ . '/../config/truth-codec.php', 'truth-codec');

        // Concrete serializers as singletons (stateless, safe to reuse).
        $this->app->singleton(JsonSerializer::class, fn () => new JsonSerializer());
        $this->app->singleton(YamlSerializer::class, fn () => new YamlSerializer());

        // Registry with named serializers for easy lookup and custom wiring.
        $this->app->singleton(SerializerRegistry::class, function ($app) {
            $reg = new SerializerRegistry([
                'json' => $app->make(JsonSerializer::class),
                'yaml' => $app->make(YamlSerializer::class),
            ]);
            return $reg;
        });

        // Default resolution: app(PayloadSerializer::class)
        // -> AutoDetect(JSON, YAML) using order/primary from config.
        $this->app->bind(PayloadSerializer::class, function ($app) {
            /** @var SerializerRegistry $reg */
            $reg = $app->make(SerializerRegistry::class);

            // Ordered list of format names to try on decode.
            $order = config('truth-codec.auto_detect_order', ['json', 'yaml']);

            // Primary controls encode() (deterministic output).
            $primaryName = config('truth-codec.primary', $order[0] ?? 'json');

            $candidates = $reg->getMany($order);
            $primary    = $reg->get($primaryName);

            return new AutoDetectSerializer($candidates, $primary);
        });
    }

    /**
     * Bootstraps publishable resources for the package.
     *
     * Allows apps to run:
     *   php artisan vendor:publish --tag=truth-codec-config
     *
     * to copy the default config into config/truth-codec.php for customization.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/truth-codec.php' => config_path('truth-codec.php'),
        ], 'truth-codec-config');
    }
}
