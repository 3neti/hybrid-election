<?php

namespace TruthQrUi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TruthQrUi\Actions\EncodePayload;

final class EncodeController extends Controller
{
    public function show()
    {
        return view('truth-qr-ui::encode');
    }

    public function encode(Request $request)
    {
        $payloadText = (string) $request->input('payload', '');
        $code        = (string) ($request->input('code') ?: 'ER-'.substr(md5(uniqid('', true)), 0, 6));

        $format   = (string) $request->input('format', config('truth-qr-ui.publish.format', 'svg')); // 'svg'|'png'
        $writer   = (string) $request->input('writer', config('truth-qr-ui.publish.writer', 'bacon')); // 'bacon'|'endroid'|'null'
        $by       = (string) $request->input('by', config('truth-qr-ui.publish.strategy', 'size'));    // 'size'|'count'
        $count    = (int) $request->input('count', config('truth-qr-ui.publish.count', 3));
        $chunk    = (int) $request->input('chunk', config('truth-qr-ui.publish.chunk', 800));
        $imgSize  = (int) $request->input('size', config('truth-qr-ui.publish.size', 800));

        $payload = $this->parseFlexiblePayload($payloadText);

        $result = app(EncodePayload::class)->run($payload, $code, [
            'format' => $format,
            'writer' => $writer,
            'by'     => $by,
            'count'  => $count,
            'chunk'  => $chunk,
            'size'   => $imgSize,
        ]);

        return view('truth-qr-ui::encode', [
            'input'  => compact('payloadText', 'code', 'format', 'writer', 'by', 'count', 'chunk', 'imgSize'),
            'result' => $result,
        ]);
    }

    private function parseFlexiblePayload(string $text): array
    {
        $trim = trim($text);
        if ($trim === '') return [];

        // Try JSON first
        $json = json_decode($trim, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            return $json;
        }

        // Try YAML (if symfony/yaml is present in host app)
        if (class_exists(\Symfony\Component\Yaml\Yaml::class)) {
            try {
                $yaml = \Symfony\Component\Yaml\Yaml::parse($trim);
                if (is_array($yaml)) {
                    return $yaml;
                }
            } catch (\Throwable $e) {
                // ignore and fallthrough
            }
        }

        // Fallback: wrap as text blob
        return ['type' => 'text', 'body' => $trim];
    }
}
