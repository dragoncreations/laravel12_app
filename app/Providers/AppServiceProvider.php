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

        BladeFilters::macro("wholeWords", function ($value) {
            return ucwords(str_replace("_", " ", $value));
        });

        BladeFilters::macro("statusColor", function ($value) {
            switch ($value) {
                case Task::STATUS_TO_DO:
                    $class = 'bg-info';
                    break;
                case Task::STATUS_IN_PROGRESS:
                    $class = 'bg-warning text-dark';
                    break;
                case Task::STATUS_DONE:
                    $class = 'bg-success';
                    break;
            }

            return $class;
        });
    }
}
