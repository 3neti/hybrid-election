<?php

namespace TruthQr\Writer\Dto;

/**
 * Aggregate of produced artifacts. Keep it flexible; you can add more fields later.
 */
final class QrWriteResult
{
    /** @param QrChunkRender[] $chunks */
    public function __construct(
        public readonly array   $chunks,
        public readonly ?string $pdfPath   = null,
        public readonly ?string $spritePng = null,  // optional spritesheet
        public readonly ?string $indexJson = null   // optional written manifest
    ) {}
}
