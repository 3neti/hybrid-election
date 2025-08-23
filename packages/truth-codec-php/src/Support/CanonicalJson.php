<?php

namespace TruthCodec\Support;

final class CanonicalJson
{
    /** Deterministic JSON (sorted keys, no pretty print) */
    public static function encode(array $data): string
    {
        // Recursively sort keys
        $sorted = self::ksortRecursive($data);
        $json = json_encode($sorted, JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException('Failed to encode canonical JSON');
        }
        return $json;
    }

    private static function ksortRecursive(array $data): array
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) $data[$k] = self::ksortRecursive($v);
        }
        ksort($data);
        return $data;
    }
}
