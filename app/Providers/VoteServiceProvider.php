<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\VoteTallyService;

class VoteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(VoteTallyService::class, function ($app) {
            return new VoteTallyService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
