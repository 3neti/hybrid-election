<?php

namespace TruthQr;

use TruthQr\Assembly\Contracts\TruthAssemblerContract;
use Illuminate\Support\ServiceProvider;
use TruthCodec\Contracts\Envelope;
use TruthCodec\Envelope\EnvelopeV1Url;   // pulled from truth-codec-php
use TruthQr\Contracts\TruthQrWriter;
use TruthQr\Writers\NullQrWriter;
use TruthQr\Writers\BaconQrWriter;
use TruthQr\Contracts\TruthStore;
use TruthQr\Stores\ArrayTruthStore;
use TruthQr\Stores\RedisTruthStore;
use TruthQr\Assembly\TruthAssembler;

class TruthQrServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(__DIR__ . '/../config/truth-qr.php', 'truth-qr');

        // Bind the Envelope used for URL generation (configurable)
        $this->app->bind(Envelope::class, function ($app) {
            // You can switch to EnvelopeV1Line if you prefer line style
            // or read a custom class name from config later.
            return new EnvelopeV1Url();
        });

        $this->app->bind(TruthQrWriter::class, function ($app) {
            $driver = config('truth-qr.driver', 'bacon');
            $fmt    = config('truth-qr.default_format', 'svg');

            if ($driver === 'bacon') {
                $cfg = config('truth-qr.bacon', []);
                return new BaconQrWriter(
                    fmt:    $fmt,
                    size:   (int)($cfg['size']   ?? 512),
                    margin: (int)($cfg['margin'] ?? 16)
                );
            }

            // Fallback: null writer
            return new NullQrWriter($fmt);
        });

        // TruthStore binding (configurable)
        $this->app->bind(TruthStore::class, function ($app) {
            $driver = config('truth-qr.store', 'array');

            if ($driver === 'redis') {
                $cfg = config('truth-qr.stores.redis', []);
                return new RedisTruthStore(
                    keyPrefix: $cfg['key_prefix'] ?? 'truth:qr:',
                    defaultTtl: (int) ($cfg['ttl'] ?? 86400),
                    connection: $cfg['connection'] ?? null
                );
            }

            $cfg = config('truth-qr.stores.array', []);
            return new ArrayTruthStore(
                defaultTtl: (int) ($cfg['ttl'] ?? 0)
            );
        });

        $this->app->singleton(TruthAssemblerContract::class, function ($app) {
            return $app->make(TruthAssembler::class);
        });

        $this->app->bind(TruthAssembler::class, function ($app) {
            return new TruthAssembler(
                store: $app->make(\TruthQr\Contracts\TruthStore::class),
                envelope: $app->make(\TruthCodec\Contracts\Envelope::class),
                transport: $app->make(\TruthCodec\Contracts\TransportCodec::class),
                serializer: $app->make(\TruthCodec\Contracts\PayloadSerializer::class),
            );
        });
    }

    public function boot(): void
    {
        // Allow publishing config
        $this->publishes([
            __DIR__ . '/../config/truth-qr.php' => config_path('truth-qr.php'),
        ], 'truth-qr-config');

        // Load package routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/truth-qr.php');

        // Register CLI command(s)
        if ($this->app->runningInConsole()) {
            $this->commands([
                \TruthQr\Console\TruthIngestFileCommand::class,
            ]);
        }
    }
}
