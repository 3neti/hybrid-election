<?php

namespace TruthCodec\Encode;

use TruthCodec\Contracts\PayloadSerializer;
use TruthCodec\Envelope\EnvelopeV1;

/**
 * Encodes an arbitrary payload (e.g., Election Return, Ballot, Canvass)
 * into a sequence of fixed-size "chunks" suitable for QR codes or
 * other constrained transport mediums.
 *
 * Each chunk is wrapped in an {@see EnvelopeV1} header containing:
 *   - A version marker (`v1`),
 *   - A caller-provided code identifying the payload set,
 *   - The index of this chunk (1-based),
 *   - The total number of chunks,
 *   - Followed by a fragment of the serialized payload.
 *
 * Example encoded line:
 *   ER|v1|XYZ|2/5|{"hello":"world"}...
 *
 * Usage:
 * ```php
 * $encoder = new ChunkEncoder(new JsonSerializer());
 * $chunks = $encoder->encodeToChunks($payload, 'XYZ', 800);
 * // $chunks is an array of strings, each safe to embed in a QR code.
 * ```
 */
class ChunkEncoder
{
    /**
     * @param PayloadSerializer $serializer Strategy for serializing payloads (e.g., JSON, YAML).
     */
    public function __construct(
        private readonly PayloadSerializer $serializer
    ) {}

    /**
     * Encode a DTO payload into multiple envelope chunks.
     *
     * @param array<string,mixed> $payload  Arbitrary associative array (Ballot, ER, Canvass, etc.).
     * @param string              $code     Identifier to appear in the envelope (groups chunks).
     * @param int                 $chunkSize Maximum payload size per chunk (default 800 chars).
     *
     * @return string[] Lines formatted as `ER|v1|<CODE>|i/N|<payloadPart>`.
     *
     * @example
     * $payload = ['type' => 'ER', 'code' => 'XYZ', 'data' => ['hello' => 'world']];
     * $encoder = new ChunkEncoder(new JsonSerializer());
     * $chunks = $encoder->encodeToChunks($payload, 'XYZ', 400);
     * // $chunks[0] = "ER|v1|XYZ|1/2|{...}"
     * // $chunks[1] = "ER|v1|XYZ|2/2|..."
     */
    public function encodeToChunks(array $payload, string $code, int $chunkSize = 800): array
    {
        $blob = $this->serializer->encode($payload);

        // Split payload into fixed-size parts
        $parts = str_split($blob, $chunkSize);
        $total = count($parts);

        $lines = [];
        foreach ($parts as $i => $part) {
            $hdr = EnvelopeV1::header($code, $i + 1, $total);
            $lines[] = "{$hdr}|{$part}";
        }
        return $lines;
    }
}
