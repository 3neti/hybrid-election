<?php

namespace TruthCodec\Decode;

use TruthCodec\Envelope\EnvelopeV1;

class ChunkDecoder
{
    public function parseLine(string $line): ChunkHeader
    {
        [$code, $idx, $tot, $payload] = EnvelopeV1::parseHeader($line);
        return new ChunkHeader($code, $idx, $tot, $payload);
    }
}
