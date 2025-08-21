<?php

namespace App\Console\Pipes;

use App\Console\Pipelines\FinalizeErContext;
use App\Services\Qr\QrExporter;
use Closure;

final class PerformQrExport
{
    public function __construct(private QrExporter $exporter) {}

    public function handle(FinalizeErContext $ctx, Closure $next)
    {
        $res = $this->exporter->export($ctx->er->code, [
            'payload'   => $ctx->payload,
            'max_chars' => $ctx->maxChars,
            'dir'       => basename($ctx->folder), // usually 'final'
        ]);

        $ctx->qrPersistedAbs = $res->persistedToAbs;

        return $next($ctx);
    }
}
