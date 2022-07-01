<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \Dotech\Order\Events\OrderValidateRequest::class => [
            \Dotech\Order\Listeners\OrderValidateRequestListener::class,
            \Dotech\Item\Listeners\OrderValidateRequestListener::class,
            \Dotech\Payment\Listeners\OrderValidateRequestListener::class,
        ],

        \Dotech\Order\Events\OrderPrepareDetail::class => [
            \Dotech\Item\Listeners\OrderPrepareDetailListener::class,
        ],

        \Dotech\Order\Events\OrderBeforeResponse::class => [
            \Dotech\Payment\Listeners\OrderBeforeResponseListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
