<?php

namespace TruthCodec\Envelope;

/**
 * EnvelopeV1
 *
 * Version 1 "envelope" format for chunked payloads.
 * Now supports configurable prefix via:
 *  - runtime override (setPrefixOverride),
 *  - Laravel config: truth-codec.envelope.prefix,
 *  - hardcoded default (self::DEFAULT_PREFIX).
 */
final class EnvelopeV1
{
    /** Hardcoded defaults (kept for BC) */
    public const DEFAULT_PREFIX = 'ER';
    public const DEFAULT_VERSION = 'v1';

    /** @var string|null runtime prefix override (highest precedence) */
    private static ?string $prefixOverride = null;

    /** @var string|null runtime version override (optional; keep default if null) */
    private static ?string $versionOverride = null;

    /**
     * Build a header string for a chunk line.
     *
     * @return string "PREFIX|VERSION|<CODE>|i/N"
     */
    public static function header(string $code, int $index, int $total): string
    {
        if ($index < 1 || $total < 1 || $index > $total) {
            throw new \InvalidArgumentException('Invalid chunk index/total');
        }

        $prefix  = self::configuredPrefix();
        $version = self::configuredVersion();

        return sprintf('%s|%s|%s|%d/%d', $prefix, $version, $code, $index, $total);
    }

    /**
     * Parse a header line of the form:
     *   PREFIX|VERSION|<CODE>|i/N|<payload>
     *
     * @return array{0:string,1:int,2:int,3:string} [code, index, total, payloadPart]
     */
    public static function parseHeader(string $line): array
    {
        $parts = explode('|', $line, 5);
        if (count($parts) < 5) {
            throw new \InvalidArgumentException('Invalid envelope');
        }

        [$pfx, $ver, $code, $idxTot, $payload] = $parts;

        $prefix  = self::configuredPrefix();
        $version = self::configuredVersion();

        if ($pfx !== $prefix || $ver !== $version) {
            throw new \InvalidArgumentException('Unsupported envelope version/prefix');
        }

        if (!preg_match('~^(\d+)\/(\d+)$~', $idxTot, $m)) {
            throw new \InvalidArgumentException('Invalid i/N segment');
        }

        return [$code, (int)$m[1], (int)$m[2], $payload];
    }

    /**
     * Runtime override APIs (useful for tests or one-off processes).
     */
    public static function setPrefixOverride(?string $prefix): void
    {
        self::$prefixOverride = $prefix !== null ? trim($prefix) : null;
    }

    public static function setVersionOverride(?string $version): void
    {
        self::$versionOverride = $version !== null ? trim($version) : null;
    }

    /**
     * Resolve configured prefix:
     *   1) runtime override
     *   2) Laravel config truth-codec.envelope.prefix
     *   3) DEFAULT_PREFIX
     */
    private static function configuredPrefix(): string
    {
        if (self::$prefixOverride !== null && self::$prefixOverride !== '') {
            return self::$prefixOverride;
        }

        // Prefer Laravel config if available
        if (function_exists('config')) {
            $cfg = config('truth-codec.envelope.prefix');
            if (is_string($cfg) && $cfg !== '') {
                return $cfg;
            }
        }

        return self::DEFAULT_PREFIX;
    }

    /**
     * Resolve configured version (same precedence as prefix).
     */
    private static function configuredVersion(): string
    {
        if (self::$versionOverride !== null && self::$versionOverride !== '') {
            return self::$versionOverride;
        }

        if (function_exists('config')) {
            $cfg = config('truth-codec.envelope.version');
            if (is_string($cfg) && $cfg !== '') {
                return $cfg;
            }
        }

        return self::DEFAULT_VERSION;
    }
}
