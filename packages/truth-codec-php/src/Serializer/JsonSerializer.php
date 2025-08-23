<?php

namespace TruthCodec\Serializer;

use TruthCodec\Contracts\PayloadSerializer;
use TruthCodec\Support\CanonicalJson;

class JsonSerializer implements PayloadSerializer
{
    public function encode(array $payload): string
    {
        return CanonicalJson::encode($payload);
    }

    public function decode(string $text): array
    {
        $data = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new \InvalidArgumentException('JSON payload must decode to array');
        }
        return $data;
    }

    public function format(): string
    {
        return 'json';
    }
}
