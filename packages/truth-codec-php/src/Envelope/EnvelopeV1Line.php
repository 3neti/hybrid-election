<?php

namespace TruthCodec\Envelope;

/**
 * V1 line (pipe-delimited) envelope:
 *   <PREFIX>|v1|<CODE>|<i>/<N>|<payload>
 *
 * Prefix & version are resolved via EnvelopeV1Common:
 *  - runtime override constants (PREFIX_OVERRIDE / VERSION_OVERRIDE),
 *  - Laravel config: truth-codec.envelope.{prefix,version},
 *  - class defaults from prefix()/version().
 */
final class EnvelopeV1Line implements EnvelopeV1Contract
{
    use EnvelopeV1Common;

    public function __construct(
        private readonly string $defaultPrefix = 'ER'
    ) {}

    /** Default logical family token (can be overridden via config/override). */
    public function prefix(): string { return 'ER'; }

    /** Envelope semantic version token. */
    public function version(): string { return 'v1'; }

    /** Human-friendly transport form. */
    public function transport(): string { return 'line'; }

    /**
     * Build a header/line for one chunk.
     * Format: PREFIX|v1|<CODE>|<i>/<N>|<payload>
     */
    public function header(string $code, int $index, int $total, string $payloadPart): string
    {
        $this->assertIndexTotal($index, $total);

        $pfx = $this->configuredPrefix();
        $ver = $this->configuredVersion();

        return sprintf('%s|%s|%s|%d/%d|%s', $pfx, $ver, $code, $index, $total, $payloadPart);
    }

    /**
     * Parse a line back to [code, index, total, payload].
     * Accepts only the configured prefix/version.
     */
    public function parse(string $encoded): array
    {
        // Expected: PREFIX|v1|<CODE>|i/N|<payload>
        $parts = explode('|', $encoded, 5);
        if (count($parts) < 5) {
            throw new \InvalidArgumentException('Invalid envelope line (expected 5 segments).');
        }

        [$pfx, $ver, $code, $idxTot, $payload] = $parts;

        if ($pfx !== $this->configuredPrefix() || $ver !== $this->configuredVersion()) {
            throw new \InvalidArgumentException('Unsupported envelope prefix/version.');
        }

        if (!preg_match('~^(\d+)\/(\d+)$~', $idxTot, $m)) {
            throw new \InvalidArgumentException('Invalid i/N segment.');
        }

        $i = (int) $m[1];
        $n = (int) $m[2];
        $this->assertIndexTotal($i, $n);

        return [$code, $i, $n, $payload];
    }
}
