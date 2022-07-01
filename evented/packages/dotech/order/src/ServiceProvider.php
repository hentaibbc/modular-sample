<?php

namespace Dotech\Order;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupRoutes();
    }

    protected function setupRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }
}
