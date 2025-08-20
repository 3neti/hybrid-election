<?php

namespace App\Services\Qr;

interface QrExporter
{
    /**
     * @param array{payload?:string,max_chars?:int,dir?:string} $opts
     */
    public function export(string $erCode, array $opts = []): QrExportResult;
}
