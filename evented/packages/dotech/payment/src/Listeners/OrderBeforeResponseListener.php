<?php

namespace Dotech\Payment\Listeners;

use Dotech\Order\Events\OrderBeforeResponse;
use Dotech\Payment\Repositories\PaymentRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderBeforeResponseListener
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
    public function handle(OrderBeforeResponse $event)
    {
        $order = $event->getOrder();
        $url = app(PaymentRepository::class)->getPaymentUrl($order);
        $event->put('paymentUrl', $url);
    }
}
