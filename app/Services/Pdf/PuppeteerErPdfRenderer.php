<?php

namespace App\Services\Pdf;

use App\Contracts\ElectionReturnPdfRenderer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Process\Process;

/**
 * Puppeteer/Chromium renderer that prints the existing Vue-driven ER page
 * served by your PrintErController route. No blade fallback required.
 *
 * Config example (config/pdf.php):
 *
 * return [
 *   'renderer' => 'puppeteer', // or 'reportlab', etc.
 *   'puppeteer' => [
 *     'binary' => '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
 *     'args'   => [
 *       '--headless=new',
 *       '--disable-gpu',
 *       '--no-sandbox',
 *       '--disable-dev-shm-usage',
 *     ],
 *     // Optional: give time for SPA to finish rendering (ms virtual-time budget)
 *     'virtual_time_budget_ms' => 12000,
 *   ],
 *   'timeout' => 60, // seconds process timeout
 * ];
 */
class PuppeteerErPdfRenderer implements ElectionReturnPdfRenderer
{
    public function __construct(private array $cfg) {}

    /**
     * Render an ER page (via route) to a PDF at $destAbs.
     *
     * $opts:
     *  - payload: 'minimal'|'full' (default: 'minimal')
     *  - paper:   'legal'|'a4'     (default: 'legal')
     *  - wait:    JS window flag to wait for (default: 'erReady')
     *  - timeout: seconds for process timeout (default: 30)
     */
    public function render(array $erJson, string $destAbs, array $opts = []): void
    {
        // ---- Safe option defaults
        $payload = \in_array($opts['payload'] ?? 'minimal', ['minimal','full'], true)
            ? ($opts['payload'] ?? 'minimal')
            : 'minimal';

        $paper = \in_array(strtolower($opts['paper'] ?? 'legal'), ['legal','a4'], true)
            ? strtolower($opts['paper'] ?? 'legal')
            : 'legal';

        $waitFlag = $opts['wait'] ?? 'erReady';
        $timeout  = (int)($opts['timeout'] ?? 30);

        // ---- Resolve Chrome/Chromium binary + args
        $bin  = $this->cfg['puppeteer']['binary'] ?? '/usr/bin/chromium';
        $args = $this->cfg['puppeteer']['args'] ?? [];

        // Early, explicit error so tests can assert cleanly
        if (!is_string($bin) || $bin === '' || !file_exists($bin)) {
            throw new \RuntimeException('Puppeteer/Chromium failed: Chrome binary not found at "'.$bin.'"');
        }

        // ---- Build the print URL (always use the route)
        // Expect your print route name to be 'print.er' and accept ?paper=&payload=
        $codeBare = ltrim((string)($erJson['code'] ?? ''), 'ER-');
        if ($codeBare === '') {
            throw new \RuntimeException('Puppeteer/Chromium failed: missing ER code.');
        }

        $url = route('print.er', ['code' => $codeBare]);
        // append query
        $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query([
                'paper'   => $paper,
                'payload' => $payload,
            ]);

        // ---- Ensure output directory exists
        @mkdir(\dirname($destAbs), 0777, true);

        // ---- Compose chrome args
        $cmd = array_merge(
            [$bin],
            $args,
            [
                '--headless=new',
                '--disable-gpu',
                '--no-sandbox',
                '--disable-dev-shm-usage',
                // Print-to-PDF switches:
                '--print-to-pdf=' . $destAbs,
                // Paper size: Legal ≈ 8.5x14in, A4 implicit unless you add emulation;
                // If you need strict paper control, consider --print-to-pdf-no-header and
                // page emulation via a launcher wrapper. For now we rely on your CSS @page.
                $url,
            ]
        );

        // Optional: wait for window.erReady=true before printing.
        // Vanilla chrome doesn’t have a direct “wait for flag” CLI; if you need it,
        // you can inject a small prelude through a local wrapper. For now rely on
        // network idle + your page’s own blocking assets. (Browsershot can do this
        // natively; with raw chrome we keep it simple.)
        $p = new Process($cmd, null, null, null, $timeout);
        $p->run();

        if (!$p->isSuccessful() || !file_exists($destAbs)) {
            $stderr = trim($p->getErrorOutput() ?: $p->getOutput());
            throw new \RuntimeException('Puppeteer/Chromium failed: ' . ($stderr !== '' ? $stderr : 'unknown error'));
        }
    }
}
