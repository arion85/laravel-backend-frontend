<?php

namespace Common\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $a=5;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $a=5;
    }
}
