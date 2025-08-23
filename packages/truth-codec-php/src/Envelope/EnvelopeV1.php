<?php

namespace TruthCodec\Envelope;

/**
 * EnvelopeV1
 *
 * Defines the version 1 "envelope" format used to wrap chunked payloads
 * for Election Returns, Ballots, Canvass data, or other DTOs that must be
 * split into multiple transport-safe lines (e.g., QR codes).
 *
 * The envelope provides a fixed prefix, version marker, and metadata for
 * each chunk, ensuring they can be validated and reassembled in order.
 *
 * Format of each line:
 *   ER|v1|<CODE>|<i>/<N>|<payloadPart>
 *
 * Where:
 * - `ER` = prefix identifying the envelope family.
 * - `v1` = version string.
 * - `<CODE>` = caller-provided identifier grouping related chunks.
 * - `<i>` = 1-based index of this chunk.
 * - `<N>` = total number of chunks in the set.
 * - `<payloadPart>` = fragment of the serialized payload.
 *
 * Example:
 *   ER|v1|XYZ|2/5|{"hello":"world"}...
 */
final class EnvelopeV1
{
    /** @var string Prefix marker for all EnvelopeV1 headers. */
    public const PREFIX = 'ER';

    /** @var string Version marker for this envelope format. */
    public const VERSION = 'v1';

    /**
     * Build a header string for a chunk line.
     *
     * @param string $code   Identifier grouping chunks (e.g., precinct code).
     * @param int    $index  1-based index of this chunk within the set.
     * @param int    $total  Total number of chunks in the set.
     *
     * @throws \InvalidArgumentException if index/total values are invalid
     *                                   (e.g., index < 1, total < 1, or index > total).
     *
     * @return string Header string of the form "ER|v1|<CODE>|i/N".
     */
    public static function header(string $code, int $index, int $total): string
    {
        if ($index < 1 || $total < 1 || $index > $total) {
            throw new \InvalidArgumentException('Invalid chunk index/total');
        }
        return sprintf('%s|%s|%s|%d/%d', self::PREFIX, self::VERSION, $code, $index, $total);
    }

    /**
     * Parse a full chunk line header and extract metadata.
     *
     * Expected input format:
     *   ER|v1|<CODE>|i/N|<payload>
     *
     * @param string $line A chunk line to parse.
     *
     * @throws \InvalidArgumentException if the line is malformed, has
     *                                   an unsupported prefix/version,
     *                                   or an invalid index/total segment.
     *
     * @return array{0:string,1:int,2:int,3:string}
     *   - [0] string: the code identifier.
     *   - [1] int: the index of this chunk (1-based).
     *   - [2] int: the total number of chunks.
     *   - [3] string: the raw payload fragment.
     */
    public static function parseHeader(string $line): array
    {
        // Expected: ER|v1|<CODE>|i/N|<payload>
        $parts = explode('|', $line, 5);
        if (count($parts) < 5) {
            throw new \InvalidArgumentException('Invalid envelope');
        }
        [$pfx, $ver, $code, $idxTot, $payload] = $parts;

        if ($pfx !== self::PREFIX || $ver !== self::VERSION) {
            throw new \InvalidArgumentException('Unsupported envelope version');
        }
        if (!preg_match('~^(\d+)\/(\d+)$~', $idxTot, $m)) {
            throw new \InvalidArgumentException('Invalid i/N segment');
        }

        return [$code, (int)$m[1], (int)$m[2], $payload];
    }
}
