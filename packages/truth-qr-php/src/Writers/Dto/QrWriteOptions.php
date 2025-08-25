<?php

namespace TruthQr\Writer\Dto;

final class QrWriteOptions
{
    public function __construct(
        public readonly int    $qrSizePx        = 512,   // rendered square size
        public readonly string $ecLevel         = 'M',   // L,M,Q,H
        public readonly int    $margin          = 16,    // quiet zone
        public readonly bool   $withCaption     = true,  // show “i/N · CODE”
        public readonly string $captionFormat   = '{index}/{total} · {code}',
        public readonly string $fontFamily      = 'Inter, Arial, sans-serif',
        public readonly int    $fontSizePt      = 12,
        public readonly bool   $compactLayout   = false, // e.g., tiles per page
        public readonly int    $columns         = 2,     // for tile/sprite/pdf grids
        public readonly int    $rows            = 3,
        public readonly ?string $codeOverride   = null,  // force code text in caption
        public readonly ?string $title          = null,  // optional document title
    ) {}
}
