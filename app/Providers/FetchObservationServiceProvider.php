<?php

namespace App\Providers;

use App\Services\FetchObservationService;
use Illuminate\Support\ServiceProvider;

class FetchObservationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(FetchObservationService::class, function () {
            return new FetchObservationService();
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
