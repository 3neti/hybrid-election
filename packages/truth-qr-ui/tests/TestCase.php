<?php

namespace TruthQrUi\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use TruthCodec\TruthCodecServiceProvider;
use TruthQr\TruthQrServiceProvider;
use TruthQrUi\TruthQrUiServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        // truth-qr-ui depends on truth-qr-php depends on truth-codec-php (Envelope class)
        return [
            TruthCodecServiceProvider::class,
            TruthQrServiceProvider::class,
            TruthQrUiServiceProvider::class,
        ];
    }
}
