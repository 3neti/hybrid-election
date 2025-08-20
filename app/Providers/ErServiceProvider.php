<?php

namespace App\Providers;

use App\Policies\Signatures\{SignaturePolicy, ChairPlusMemberPolicy};
use App\Services\Qr\{QrExporter, HttpQrExporter};
use App\Contracts\ElectionReturnPdfRenderer;
use App\Services\Pdf\PuppeteerErPdfRenderer;
use Illuminate\Support\ServiceProvider;
// use App\Services\Pdf\ReportLabErPdfRenderer; // optional, if you add it

class ErServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $cfg = config('er.pdf');
        $driver = strtolower($cfg['driver'] ?? 'puppeteer');

        $this->app->singleton(ElectionReturnPdfRenderer::class, function () use ($cfg, $driver) {
            return match ($driver) {
                'puppeteer' => new PuppeteerErPdfRenderer($cfg),
                // 'reportlab' => new ReportLabErPdfRenderer($cfg),
                default     => new PuppeteerErPdfRenderer($cfg),
            };
        });

        $this->app->bind(SignaturePolicy::class, ChairPlusMemberPolicy::class);
        $this->app->bind(QrExporter::class, HttpQrExporter::class);
    }
}
