<?php

namespace TruthCodec\Serializer;

use TruthCodec\Contracts\PayloadSerializer;
use Symfony\Component\Yaml\Yaml;

class YamlSerializer implements PayloadSerializer
{
    public function encode(array $payload): string
    {
        return Yaml::dump($payload, 10, 2, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE);
    }

    public function decode(string $text): array
    {
        $data = Yaml::parse($text);
        if (!is_array($data)) {
            throw new \InvalidArgumentException('YAML payload must decode to array');
        }
        return $data;
    }

    public function format(): string
    {
        return 'yaml';
    }
}
