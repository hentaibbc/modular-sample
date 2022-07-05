<?php

namespace Dotech\Shipping\Listeners;

use Dotech\Order\Events\OrderValidateRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Validator;

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

        $errors = Validator::make($request->all(), [
            'shipping.id'   => 'required|in:takeaway,inner,delivery',
        ])->errors();
        $event->pushErrors($errors);
    }
}
