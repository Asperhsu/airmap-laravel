<?php

namespace App\Providers;

use App\Service\Geometry;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Log::getMonolog()->popHandler();
        \Log::useDailyFiles(storage_path('/logs/laravel-').gethostname().'.log');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Geometry::class, function ($app) {
            return new Geometry();
        });
    }
}
