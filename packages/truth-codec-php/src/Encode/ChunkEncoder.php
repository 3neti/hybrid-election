<?php

namespace TruthCodec\Encode;

use TruthCodec\Contracts\PayloadSerializer;
use TruthCodec\Envelope\EnvelopeV1;

class ChunkEncoder
{
    public function __construct(
        private readonly PayloadSerializer $serializer
    ) {}

    /**
     * @param array $payload arbitrary DTO array (ballot/ER/canvass)
     * @param string $code   identifier to appear in the envelope
     * @param int $chunkSize max payload size per chunk (safe QR size)
     * @return string[] lines of "ER|v1|<CODE>|i/N|<payloadPart>"
     */
    public function encodeToChunks(array $payload, string $code, int $chunkSize = 800): array
    {
        $blob = $this->serializer->encode($payload);

        // Split into url-safe base64-ish with - _ and no padding (or keep raw)
        // Here we keep raw text; if you need base64-url, transform here.
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
