<?php

namespace Dotech\Order\Listeners;

use Dotech\Order\Events\OrderValidateRequest;
use Dotech\Order\Repositories\OrderRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderValidateRequestListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderValidateRequest $event)
    {
        $request = $event->getRequest();

        $errors = app(OrderRepository::class)->validate($request);
        $event->pushErrors($errors);
    }
}
