<?php

namespace Dotech\PaymentLinepay\Services;

use Dotech\Order\Models\Order;
use Dotech\Payment\Services\Contracts\PaymentService;

class Linepay implements PaymentService
{
    public function getPaymentUrl(Order $order): string
    {
        return 'http://linepay?order='.$order['id'];
    }
}