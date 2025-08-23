<?php

namespace TruthCodec\Decode;

final class ChunkHeader
{
    public function __construct(
        public readonly string $code,
        public readonly int $index,
        public readonly int $total,
        public readonly string $payloadPart
    ){}
}
