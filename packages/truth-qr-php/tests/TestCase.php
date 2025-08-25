<?php

declare(strict_types=1);

namespace TruthQr\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use TruthQr\TruthQrServiceProvider;
use TruthCodec\TruthCodecServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        // truth-qr-php depends on truth-codec-php (for Envelope classes)
        return [
            TruthCodecServiceProvider::class,
            TruthQrServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        // Optional: override any config defaults here during tests
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
