<?php

namespace App\Services\Pdf;

use App\Contracts\ElectionReturnPdfRenderer;
use Throwable;

class ElectionReturnPdfManager
{
    public function __construct(
        private ElectionReturnPdfRenderer $primary,
        private ?ElectionReturnPdfRenderer $fallback = null,
    ) {}

    public function render(array $erJson, string $destAbs, array $opts = []): void
    {
        try {
            $this->primary->render($erJson, $destAbs, $opts);
        } catch (Throwable $e) {
            if (!$this->fallback) throw $e;
            // log primary failure
            logger()->warning('ER PDF primary failed; attempting fallback', ['error' => $e->getMessage()]);
            $this->fallback->render($erJson, $destAbs, $opts);
        }
    }
}
