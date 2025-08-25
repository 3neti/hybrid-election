<?php

namespace TruthQr;

use Illuminate\Support\ServiceProvider;
use TruthCodec\Contracts\Envelope;
use TruthCodec\Envelope\EnvelopeV1Url;   // pulled from truth-codec-php
use TruthQr\Contracts\TruthQrWriter;
use TruthQr\Writers\NullQrWriter;
use TruthQr\Writers\BaconQrWriter;

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

        // Bind a QR writer (swap later with Endroid/Bacon/Built-in)
//        $this->app->bind(TruthQrWriter::class, function ($app) {
//            // Start with a null writer that returns raw data/URIs for tests
//            return new NullQrWriter(config('truth-qr.default_format', 'png'));
//        });


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
    }

    public function boot(): void
    {
        // Allow publishing config
        $this->publishes([
            __DIR__ . '/../config/truth-qr.php' => config_path('truth-qr.php'),
        ], 'truth-qr-config');
    }
}
