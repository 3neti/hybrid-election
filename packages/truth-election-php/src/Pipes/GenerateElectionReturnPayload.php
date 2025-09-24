<?php

namespace TruthElection\Pipes;

use TruthCodec\Transport\Base64UrlDeflateTransport;
use TruthCodec\Serializer\JsonSerializer;
use TruthElection\Data\FinalizeErContext;
use Illuminate\Support\Facades\Storage;
use TruthCodec\Envelope\EnvelopeV1Line;
use TruthQrUi\Actions\EncodePayload;
use TruthQr\Writers\BaconQrWriter;
use Closure;

class GenerateElectionReturnPayload
{
    public function handle(FinalizeErContext $ctx, Closure $next): FinalizeErContext
    {
        $erArray = $ctx->er->toArray();
        $code = $ctx->er->code;

        // Collaborators
        $serializer = new JsonSerializer();
        $transport  = new Base64UrlDeflateTransport();
        $envelope   = new EnvelopeV1Line();

        // QR Writer – using modern constructor signature (fmt, size, margin)
        $writerFqcn = \TruthQr\Writers\BaconQrWriter::class;
        $writerFmt = 'svg';
        $writerSize = 512;
        $writerMargin = 16;

        $writer = new $writerFqcn(
            fmt: $writerFmt,
            size: $writerSize,
            margin: $writerMargin
        );

        $qrMeta = app(EncodePayload::class)->handle(
            payload: $erArray,
            code: $code,
            serializer: $serializer,
            transport: $transport,
            envelope: $envelope,
            writer: $writer,
            opts: ['by' => 'size', 'size' => $ctx->maxChars]
        );

        $payload = [
            'templateName' => 'core:precinct/er_qr/template',
            'format' => 'pdf',
            'data' => [
                'tallyMeta' => $ctx->er,
                'qrMeta' => $qrMeta,
            ],
        ];

        context('payload', $payload);

        Storage::disk($ctx->disk)->put(
            "{$ctx->folder}/ER-{$code}-payload.json",
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return $next($ctx);
    }
}
