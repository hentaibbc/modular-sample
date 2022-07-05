<?php

namespace Dotech\PaymentNewepay\Drivers;

use Dotech\Order\Models\Order;
use Dotech\Payment\Drivers\Driver;

class Newepay extends Driver
{
    public function getPaymentUrl(Order $order): string
    {
        return 'http://newepay?order='.$order['id'];
    }
}