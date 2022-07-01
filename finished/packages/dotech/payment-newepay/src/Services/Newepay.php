<?php

namespace Dotech\PaymentNewepay\Services;

use Dotech\Order\Models\Order;
use Dotech\Payment\Services\Contracts\PaymentService;

class Newepay implements PaymentService
{
    public function getPaymentUrl(Order $order): string
    {
        return 'http://newepay?order='.$order['id'];
    }
}