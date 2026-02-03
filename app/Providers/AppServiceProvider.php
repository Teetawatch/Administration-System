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
        // Override public path for subdirectory hosting
        // ตรวจสอบว่ากำลังรันบน production hosting หรือไม่
        if (str_contains(base_path(), 'adm-core')) {
            $this->app->usePublicPath(base_path('../public_html/adm'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}


