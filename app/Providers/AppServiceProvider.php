<?php

namespace App\Providers;

use App\Models\Task;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Pine\BladeFilters\BladeFilters;

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
        Paginator::useBootstrapFive();
    }
}
