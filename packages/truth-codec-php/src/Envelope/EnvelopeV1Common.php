<?php

namespace TruthCodec\Envelope;

/**
 * Shared config + guards for Envelope v1.
 *
 * Precedence for prefix/version:
 *  1) Runtime override via class constants PREFIX_OVERRIDE / VERSION_OVERRIDE (if defined).
 *  2) Laravel config: truth-codec.envelope.prefix / truth-codec.envelope.version
 *  3) Implementor defaults via prefix() / version()
 *
 * Concrete envelopes must also expose transport(): "line" or "url".
 */
trait EnvelopeV1Common
{
    protected function configuredPrefix(): string
    {
        $const = static::class.'::PREFIX_OVERRIDE';
        $override = \defined($const) ? \constant($const) : null;
        if (is_string($override) && $override !== '') return $override;

        if (function_exists('config')) {
            $cfg = config('truth-codec.envelope.prefix');
            if (is_string($cfg) && $cfg !== '') return $cfg;
        }

        return $this->prefix();
    }

    protected function configuredVersion(): string
    {
        $const = static::class.'::VERSION_OVERRIDE';
        $override = \defined($const) ? \constant($const) : null;
        if (is_string($override) && $override !== '') return $override;

        if (function_exists('config')) {
            $cfg = config('truth-codec.envelope.version');
            if (is_string($cfg) && $cfg !== '') return $cfg;
        }

        return $this->version();
    }

    /** Guard for 1 ≤ index ≤ total and total ≥ 1 */
    protected function assertIndexTotal(int $index, int $total): void
    {
        if ($index < 1 || $total < 1 || $index > $total) {
            throw new \InvalidArgumentException("Invalid chunk index/total: {$index}/{$total}");
        }
    }
}
