<?php

namespace App\Services\Qr;

use Illuminate\Support\Facades\Http;

final class HttpQrExporter implements QrExporter
{
    public function export(string $erCode, array $opts = []): QrExportResult
    {
        $payload  = in_array(($opts['payload'] ?? 'minimal'), ['minimal','full'], true) ? $opts['payload'] : 'minimal';
        $maxChars = (int)($opts['max_chars'] ?? 1200);
        $dir      = (string)($opts['dir'] ?? 'final');

        $url = route('qr.er', ['code' => $erCode]) . '?' . http_build_query([
                'payload'          => $payload,
                'make_images'      => 1,
                'max_chars_per_qr' => $maxChars,
                'persist'          => 1,
                'persist_dir'      => $dir,
            ]);

        $res = Http::acceptJson()->get($url);
        if (! $res->ok()) {
            throw new \RuntimeException('QR export failed: '.$res->body());
        }

        $j = $res->json();
        return new QrExportResult(
            total: (int)($j['total'] ?? 0),
            persistedToAbs: $j['persisted_to'] ?? null
        );
    }
}
