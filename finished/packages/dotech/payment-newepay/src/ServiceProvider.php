<?php

namespace Dotech\PaymentNewepay;

use Dotech\PaymentNewepay\Services\Newepay;
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
        app()->bind('payment.newepay', Newepay::class);
    }
}
