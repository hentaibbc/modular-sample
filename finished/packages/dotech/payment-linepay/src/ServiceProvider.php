<?php

namespace Dotech\PaymentLinepay;

use Dotech\PaymentLinepay\Services\Linepay;
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
        app()->bind('payment.linepay', Linepay::class);
    }
}
