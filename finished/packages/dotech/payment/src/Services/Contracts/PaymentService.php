<?php

namespace Dotech\Payment\Services\Contracts;

use Dotech\Order\Models\Order;

interface PaymentService
{
    public function getPaymentUrl(Order $order): string;
}