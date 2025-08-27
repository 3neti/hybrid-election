<?php

namespace TruthQrUi\Actions;

use TruthQr\Publishing\TruthQrPublisherFactory;
use TruthQr\Contracts\TruthQrWriter;

/**
 * Wraps publisher + writer selection for the UI.
 */
final class EncodePayload
{
    /**
     * @param array<string,mixed> $payload
     * @param string              $code
     * @param array<string,mixed> $opts ['format','writer','by','count','chunk','size']
     * @return array{
     *   code:string,
     *   by:string,
     *   urls: string[],
     *   qr?: array<int,string>  // [index => binary image or svg string]
     * }
     */
    public function run(array $payload, string $code, array $opts = []): array
    {
        $by     = $opts['by']    ?? config('truth-qr-ui.publish.strategy', 'size');
        $count  = (int) ($opts['count'] ?? config('truth-qr-ui.publish.count', 3));
        $chunk  = (int) ($opts['chunk'] ?? config('truth-qr-ui.publish.chunk', 800));
        $format = $opts['format'] ?? config('truth-qr-ui.publish.format', 'svg');
        $writerDriver = $opts['writer'] ?? config('truth-qr-ui.publish.writer', 'bacon');
        $imgSize = (int) ($opts['size'] ?? config('truth-qr-ui.publish.size', 800));

        // URLs via publisher factory (uses truth-qr-php under the hood)
        /** @var TruthQrPublisherFactory $factory */
        $factory = app(TruthQrPublisherFactory::class);

        $publisherOptions = ($by === 'count')
            ? ['by' => 'count', 'count' => $count]
            : ['by' => 'size',  'size'  => $chunk];

        $urls = $factory->publish($payload, $code, $publisherOptions);

        // Build QR images via configured writer
        // Allow switching writer/format at runtime for the UI
        config()->set('truth-qr.writer.driver', $writerDriver);
        config()->set('truth-qr.writer.format', $format);
        config()->set('truth-qr.writer.'. $writerDriver .'.size', $imgSize);

        /** @var TruthQrWriter $writer */
        $writer = app(TruthQrWriter::class);

        $qr = $writer->write($urls);   // keys preserved by underlying writer

        return [
            'code' => $code,
            'by'   => $by,
            'urls' => array_values($urls),
            'qr'   => $qr,
        ];
    }
}
