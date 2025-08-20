<?php

namespace App\Contracts;

interface ElectionReturnPdfRenderer
{
    /**
     * @param array  $erJson  Full ER JSON (or “minimal” variant—pick one and stick to it)
     * @param string $destAbs Absolute path for output PDF (must end in .pdf)
     * @param array  $opts    Optional parameters (e.g., 'landscape' => true)
     * @return void  (throw exceptions on failure)
     */
    public function render(array $erJson, string $destAbs, array $opts = []): void;
}
