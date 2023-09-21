<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ExportServiceInterface;
use App\Services\ExportToCsvService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExportServiceInterface::class, ExportToCsvService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
