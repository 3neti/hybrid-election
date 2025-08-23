<?php

namespace TruthCodec;

use Illuminate\Support\ServiceProvider;
use TruthCodec\Serializer\JsonSerializer;
use TruthCodec\Serializer\YamlSerializer;

class TruthCodecServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(JsonSerializer::class, fn () => new JsonSerializer());
        $this->app->bind(YamlSerializer::class, fn () => new YamlSerializer());
    }
}
