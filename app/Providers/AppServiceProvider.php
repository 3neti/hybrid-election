<?php

namespace App\Providers;

use App\Services\Pdf\{ElectionReturnPdfManager, PuppeteerErPdfRenderer, ReportLabErPdfRenderer};
use App\Contracts\ElectionReturnPdfRenderer;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ElectionReturnPdfRenderer::class, function ($app) {
            $cfg = config('pdf.er');
            $primary = match ($cfg['driver'] ?? 'puppeteer') {
                'reportlab' => new ReportLabErPdfRenderer($cfg),
                default     => new PuppeteerErPdfRenderer($cfg),
            };
            $fallback = null;
            if (!empty($cfg['fallback_to'])) {
                $fallback = match ($cfg['fallback_to']) {
                    'reportlab' => new ReportLabErPdfRenderer($cfg),
                    'puppeteer' => new PuppeteerErPdfRenderer($cfg),
                    default     => null,
                };
            }
            return new ElectionReturnPdfManager($primary, $fallback);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }


    protected function configureRateLimiting(): void
    {
        /**
         * Ingest limiter:
         * - Authenticated users: 60 req/min keyed by user id
         * - Guests/anonymous:    10 req/min keyed by IP
         * - Bursts are okay (retry after auto-handled by Laravel)
         */
        RateLimiter::for('ingest', function (Request $request) {
            if ($user = $request->user()) {
                return [
                    Limit::perMinute(60)->by('user:'.$user->getAuthIdentifier()),
                ];
            }
            return [
                Limit::perMinute(10)->by('ip:'.$request->ip()),
            ];
        });

        /**
         * Initialization limiter (optional, conservative):
         * - Global-ish key to prevent hammering init path: 3 req / minute
         * - If you expose /api/initialize-system publicly, keep this tight.
         */
        RateLimiter::for('init', function (Request $request) {
            // You can scope by env/app name if multiple apps share Redis
            return [
                Limit::perMinute(3)->by('init-global'),
            ];
        });
    }
}
