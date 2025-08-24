<?php

namespace TruthCodec\Envelope;

use TruthCodec\Contracts\Envelope;

/**
 * V1 URL envelope.
 *
 * Supports either:
 *  - deep link:  truth://v1/<prefix>/<code>/<i>/<n>?c=<payload>
 *  - web link:   https://…?truth=v1&prefix=<prefix>&code=<code>&i=<i>&n=<n>&c=<payload>
 *
 * Prefix & version are resolved via EnvelopeV1Common (override/config/default).
 */
final class EnvelopeV1Url implements Envelope
{
    use EnvelopeV1Common;

    /** URL query key carrying the payload fragment. */
    private string $payloadParam;

    /** Optional alternative version param for web links (defaults to 'truth'). */
    private string $versionParam;

    /** Optional web base (when present, we emit http(s) link instead of deep link). */
    private ?string $webBase;

    /** Deep-link scheme for truth URLs (e.g., "truth"). */
    private string $scheme;

    public function __construct(
        string $payloadParam = 'c',
        string $versionParam = 'truth',
        ?string $webBase = null,
        string $scheme = 'truth'
    ) {
        $this->payloadParam = $payloadParam;
        $this->versionParam = $versionParam;
        $this->webBase      = $webBase;
        $this->scheme       = $scheme;
    }

    /** Default logical family token (can be overridden via config/override). */
    public function prefix(): string { return 'ER'; }

    /** Envelope semantic version token. */
    public function version(): string { return 'v1'; }

    /** Human-friendly transport form. */
    public function transport(): string { return 'url'; }

    /**
     * Build a deep link (truth://…) or web URL (http/https) carrying one chunk.
     */
    public function header(string $code, int $index, int $total, string $payloadPart): string
    {
        $this->assertIndexTotal($index, $total);

        $pfx = $this->configuredPrefix();
        $ver = $this->configuredVersion();

        if ($this->webBase === null) {
            // truth://v1/<prefix>/<code>/<i>/<n>?c=<payload>
            $path = sprintf(
                '%s/%s/%s/%d/%d',
                rawurlencode($ver),
                rawurlencode($pfx),
                rawurlencode($code),
                $index,
                $total
            );
            $qs = http_build_query([$this->payloadParam => $payloadPart], '', '&', PHP_QUERY_RFC3986);
            return sprintf('%s://%s?%s', $this->scheme, $path, $qs);
        }

        // https://…?truth=v1&prefix=<pfx>&code=<code>&i=<i>&n=<n>&c=<payload>
        $q = [
            $this->versionParam => $ver,
            'prefix'            => $pfx,
            'code'              => $code,
            'i'                 => $index,
            'n'                 => $total,
            $this->payloadParam => $payloadPart,
        ];
        $qs = http_build_query($q, '', '&', PHP_QUERY_RFC3986);
        return rtrim($this->webBase, '/') . '?' . $qs;
    }

    /**
     * Parse a truth:// deep link or an http(s) URL back to [code, index, total, payload].
     */
    public function parse(string $encoded): array
    {
        $pfx = $this->configuredPrefix();
        $ver = $this->configuredVersion();

        // Deep link?
        if (str_starts_with($encoded, $this->scheme . '://')) {
            $u = parse_url($encoded);
            if (!$u || !isset($u['host'])) {
                throw new \InvalidArgumentException('Invalid truth:// URL.');
            }

            // host + path → "v1/<prefix>/<code>/<i>/<n>"
            $path  = $u['host'] . ($u['path'] ?? '');
            $segs  = array_values(array_filter(explode('/', $path), 'strlen'));
            if (count($segs) < 5) {
                throw new \InvalidArgumentException('Invalid deep-link path segments.');
            }

            [$vSeg, $pfxSeg, $code, $i, $n] = $segs;

            if ($vSeg !== $ver || $pfxSeg !== $pfx) {
                throw new \InvalidArgumentException('Prefix/version mismatch.');
            }
            if (!isset($u['query'])) {
                throw new \InvalidArgumentException('Missing payload query segment.');
            }

            parse_str($u['query'], $q);
            $payload = $q[$this->payloadParam] ?? null;
            if (!is_string($payload)) {
                throw new \InvalidArgumentException('Missing payload parameter.');
            }

            $i = (int) $i;
            $n = (int) $n;
            $this->assertIndexTotal($i, $n);
            return [$code, $i, $n, $payload];
        }

        // Web URL?
        if (str_starts_with($encoded, 'http://') || str_starts_with($encoded, 'https://')) {
            $u = parse_url($encoded);
            if (!$u || !isset($u['query'])) {
                throw new \InvalidArgumentException('Invalid http(s) URL envelope.');
            }
            parse_str($u['query'], $q);

            $v  = $q[$this->versionParam] ?? null;
            $pf = $q['prefix'] ?? null;
            $cd = $q['code']   ?? null;
            $i  = isset($q['i']) ? (int) $q['i'] : null;
            $n  = isset($q['n']) ? (int) $q['n'] : null;
            $pl = $q[$this->payloadParam] ?? null;

            if ($v !== $ver || $pf !== $pfx || !is_string($cd) || !is_string($pl) || !is_int($i) || !is_int($n)) {
                throw new \InvalidArgumentException('Missing/invalid URL envelope parameters.');
            }

            $this->assertIndexTotal($i, $n);
            return [$cd, $i, $n, $pl];
        }

        throw new \InvalidArgumentException('Unsupported envelope string (expected truth:// or http(s) URL).');
    }
}
