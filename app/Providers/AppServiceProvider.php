<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\HolidayService::class,
            \App\Services\HolidayService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('testing', 'portfolio')) {
            Notification::fake();
        }
    }
}
