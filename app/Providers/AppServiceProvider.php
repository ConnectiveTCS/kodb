<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Increase the PHP timeout limit for import operation
        if (request()->is('speakers/import')) {
            set_time_limit(300); // 5 minutes for import
        }
    }
}
