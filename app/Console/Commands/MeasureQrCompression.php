<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MeasureQrCompression extends Command
{
    protected $signature = 'qr:measure
        {--file= : Path to a JSON file to measure}
        {--max=1200 : Max chars per QR (after base64url)}
        {--desired=0 : Desired chunk count (optional, to compute suggested max)}
    ';

    protected $description = 'Measure raw, DEFLATE, Base64URL sizes and estimate QR chunk counts.';

    public function handle(): int
    {
        $file = $this->option('file');
        if (!$file || !is_readable($file)) {
            $this->error('Please provide --file=PATH to a readable JSON file.');
            return self::INVALID;
        }

        $raw = file_get_contents($file);
        $rawLen = strlen($raw);

        // Pretty common knobs in your app
        $level = 9;
        $deflate = gzdeflate($raw, $level);              // raw DEFLATE (no header)
        $zipLen  = strlen($deflate);

        $b64 = rtrim(strtr(base64_encode($deflate), '+/', '-_'), '='); // Base64URL
        $b64Len = strlen($b64);

        $max = (int) $this->option('max');
        $desired = (int) $this->option('desired');

        $chunksAtMax = (int) ceil($b64Len / max(1, $max));
        $suggestedMax = null;
        if ($desired > 0) {
            $suggestedMax = (int) ceil($b64Len / $desired);
        }

        // Also show gzip (same deflate core, with headers), just for comparison with .zip/.gz tools
        $gzip = gzencode($raw, $level);
        $gzipLen = strlen($gzip);

        $this->line('');
        $this->info('QR / Compression measurement');
        $this->line('-----------------------------');
        $this->line(sprintf('File: %s', $file));
        $this->line(sprintf('Raw JSON:     %9d bytes (%.2f KB)', $rawLen, $rawLen/1024));
        $this->line(sprintf('DEFLATE:      %9d bytes (%.2f KB)', $zipLen, $zipLen/1024));
        $this->line(sprintf('Base64URL:    %9d chars (%.2f KB text)', $b64Len, $b64Len/1024));
        $this->line(sprintf('gzip (hdrs):  %9d bytes (%.2f KB) [for comparison]', $gzipLen, $gzipLen/1024));

        $this->line('');
        $this->line(sprintf('With max_chars_per_qr=%d → ~%d chunk(s)', $max, $chunksAtMax));
        if ($desired > 0) {
            $this->line(sprintf('For desired_chunks=%d → suggested max_chars_per_qr ≈ %d', $desired, $suggestedMax));
        }

        $this->line('');
        $this->line('Rule of thumb: Base64URL adds ~33% over the DEFLATE size, so total QR text ≈ ceil(deflate_len * 4 / 3).');
        $this->line('Chunk count ≈ ceil(base64url_len / max_chars_per_qr).');

        return self::SUCCESS;
    }
}
