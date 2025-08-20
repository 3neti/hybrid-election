<?php

namespace App\Services\Qr;

final class QrExportResult
{
    public function __construct(
        public readonly int $total,
        public readonly ?string $persistedToAbs
    ) {}
}
