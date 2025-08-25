<?php

namespace TruthQr\Writer\Dto;

/**
 * Description of a single rendered QR tile.
 */
final class QrChunkRender
{
    public function __construct(
        public readonly int    $index,             // 1-based
        public readonly int    $total,
        public readonly string $code,              // parsed code for caption
        public readonly string $line,              // original envelope line
        public readonly ?string $pngPath = null,   // if written to disk
        public readonly ?string $pngData = null,   // base64 or raw binary (impl-defined)
        public readonly ?string $svgData = null    // optional
    ) {}
}
