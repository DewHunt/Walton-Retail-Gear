<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceRepositoriesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\HomeInterface','App\Repositories\HomeRepository');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
