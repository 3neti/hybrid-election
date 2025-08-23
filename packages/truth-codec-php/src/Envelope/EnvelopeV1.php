<?php

namespace TruthCodec\Envelope;

final class EnvelopeV1
{
    public const PREFIX = 'ER';
    public const VERSION = 'v1';

    /** Build the header for a chunk line. */
    public static function header(string $code, int $index, int $total): string
    {
        if ($index < 1 || $total < 1 || $index > $total) {
            throw new \InvalidArgumentException('Invalid chunk index/total');
        }
        return sprintf('%s|%s|%s|%d/%d', self::PREFIX, self::VERSION, $code, $index, $total);
    }

    /** Parse a chunk header; returns [code, index, total] */
    public static function parseHeader(string $line): array
    {
        // Expected: ER|v1|<CODE>|i/N|<payload>
        $parts = explode('|', $line, 5);
        if (count($parts) < 5) throw new \InvalidArgumentException('Invalid envelope');
        [$pfx,$ver,$code,$idxTot,$payload] = $parts;
        if ($pfx !== self::PREFIX || $ver !== self::VERSION) {
            throw new \InvalidArgumentException('Unsupported envelope version');
        }
        if (!preg_match('~^(\d+)\/(\d+)$~', $idxTot, $m)) {
            throw new \InvalidArgumentException('Invalid i/N segment');
        }
        return [$code, (int)$m[1], (int)$m[2], $payload];
    }
}
