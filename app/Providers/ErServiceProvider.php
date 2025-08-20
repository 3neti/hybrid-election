<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ElectionReturnPdfRenderer;
use App\Services\Pdf\PuppeteerErPdfRenderer;
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
    }
}
