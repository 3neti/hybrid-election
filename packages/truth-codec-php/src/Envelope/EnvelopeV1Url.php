<?php

namespace TruthCodec\Envelope;

use TruthCodec\Contracts\Envelope;

/**
 * URL-based V1 envelope.
 *
 * Supports:
 *  • Deep link: truth://v1/<prefix>/<code>/<i>/<n>?c=<payload>
 *  • Web link : https://host/path?truth=v1&prefix=...&code=...&i=...&n=...&c=...
 *
 * Configuration (config/truth-codec.php):
 *
 *  'envelope' => [
 *      'prefix'  => 'ER',        // used by both line + url variants (can be ER/BAL/TRUTH/etc.)
 *      'version' => 'v1',
 *  ],
 *  'url' => [
 *      'scheme'        => 'truth',                        // deep-link scheme
 *      'web_base'      => null,                           // if set, produce https URL instead of deep link
 *      'payload_param' => 'c',                            // query key for payload
 *      'version_param' => 'v',                            // alt key if "truth" not desired
 *  ],
 */
final class EnvelopeV1Url implements Envelope
{
    use EnvelopeV1Common;

    /** Default logical family token (falls back if no config/override). */
    public function prefix(): string { return 'ER'; }

    /** Envelope semantic version token. */
    public function version(): string { return 'v1'; }

    /** Human-friendly transport form. */
    public function transport(): string { return 'url'; }

    /**
     * Build a URL for one chunk.
     * If 'url.web_base' is null → deep link (truth://…)
     * else → web link (https://…?truth=v1&prefix=…&code=…&i=…&n=…&c=…)
     */
    public function header(string $code, int $index, int $total, string $payloadPart): string
    {
        $this->assertIndexTotal($index, $total);

        $pfx = $this->configuredPrefix();
        $ver = $this->configuredVersion();

        // Resolve URL options with defaults
        $scheme       = $this->cfg('url.scheme', 'truth');
        $webBase      = $this->cfg('url.web_base', null);
        $payloadParam = $this->cfg('url.payload_param', 'c');
        $versionParam = $this->cfg('url.version_param', 'v'); // used only as fallback reader in parse()

        // Prefer deep-link when no web_base configured
        if ($webBase === null) {
            // truth://v1/<prefix>/<code>/<i>/<n>?c=payload
            $path = sprintf(
                '%s/%s/%s/%d/%d',
                rawurlencode($ver),
                rawurlencode($pfx),
                rawurlencode($code),
                $index,
                $total
            );
            $qs = http_build_query([$payloadParam => $payloadPart], '', '&', PHP_QUERY_RFC3986);
            return sprintf('%s://%s?%s', $scheme, $path, $qs);
        }

        // https://host/path?truth=v1&prefix=ER&code=XYZ&i=2&n=5&c=payload
        $q = [
            'truth'      => $ver,
            'prefix'     => $pfx,
            'code'       => $code,
            'i'          => $index,
            'n'          => $total,
            $payloadParam => $payloadPart,
        ];
        $qs = http_build_query($q, '', '&', PHP_QUERY_RFC3986);
        return rtrim($webBase, '/') . '?' . $qs;
    }

    /**
     * Parse a deep-link or web URL back to [code, index, total, payloadPart].
     */
    public function parse(string $encoded): array
    {
        $pfx = $this->configuredPrefix();
        $ver = $this->configuredVersion();

        $scheme       = $this->cfg('url.scheme', 'truth');
        $payloadParam = $this->cfg('url.payload_param', 'c');
        $versionParam = $this->cfg('url.version_param', 'v');

        // Deep-link: truth://…
        if (str_starts_with($encoded, $scheme . '://')) {
            $u = parse_url($encoded);
            if (!$u || !isset($u['host'])) {
                throw new \InvalidArgumentException('Invalid deep-link URL.');
            }

            // host + path → "v1/<prefix>/<code>/<i>/<n>"
            $path = $u['host'] . ($u['path'] ?? '');
            $parts = array_values(array_filter(explode('/', $path), 'strlen'));
            if (count($parts) < 5) {
                throw new \InvalidArgumentException('Invalid deep-link path segments.');
            }

            [$v, $p, $code, $i, $n] = $parts;

            if ($v !== $ver)    throw new \InvalidArgumentException('Envelope version mismatch.');
            if ($p !== $pfx)    throw new \InvalidArgumentException('Envelope prefix mismatch.');

            if (!isset($u['query'])) {
                throw new \InvalidArgumentException('Missing deep-link query.');
            }
            parse_str($u['query'], $q);

            $payload = $q[$payloadParam] ?? null;
            if (!is_string($payload)) {
                throw new \InvalidArgumentException('Missing payload segment.');
            }

            $i = (int) $i;
            $n = (int) $n;
            $this->assertIndexTotal($i, $n);

            return [$code, $i, $n, $payload];
        }

        // Web URL: http(s)://…
        if (str_starts_with($encoded, 'http://') || str_starts_with($encoded, 'https://')) {
            $u = parse_url($encoded);
            if (!$u || !isset($u['query'])) {
                throw new \InvalidArgumentException('Invalid web URL envelope.');
            }
            parse_str($u['query'], $q);

            $v = $q['truth'] ?? $q[$versionParam] ?? null;
            $c = $q['code'] ?? null;
            $i = isset($q['i']) ? (int) $q['i'] : null;
            $n = isset($q['n']) ? (int) $q['n'] : null;
            $p = $q['prefix'] ?? null;
            $payload = $q[$payloadParam] ?? null;

            if ($v !== $ver || $p !== $pfx || !is_string($c) || !is_string($payload) || !is_int($i) || !is_int($n)) {
                throw new \InvalidArgumentException('Missing/invalid URL envelope params.');
            }

            $this->assertIndexTotal($i, $n);
            return [$c, $i, $n, $payload];
        }

        throw new \InvalidArgumentException('Unsupported envelope string (not a recognized URL).');
    }

    /**
     * Convenience config reader under the package namespace.
     * @param mixed $default
     * @return mixed
     */
    private function cfg(string $key, $default = null)
    {
        if (function_exists('config')) {
            $full = "truth-codec.$key";
            return config($full, $default);
        }
        return $default;
    }
}
