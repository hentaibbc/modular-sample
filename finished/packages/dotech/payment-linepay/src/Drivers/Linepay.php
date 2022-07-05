<?php

namespace Dotech\PaymentLinepay\Drivers;

use Dotech\Order\Models\Order;
use Dotech\Payment\Drivers\Driver;

class Linepay extends Driver
{
    public function getPaymentUrl(Order $order): string
    {
        return 'http://linepay?order='.$order['id'];
    }
}