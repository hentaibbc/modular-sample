<?php

namespace Dotech\Payment\Drivers\Contracts;

use Dotech\Order\Models\Order;

interface Payable
{
    public function getPaymentUrl(Order $order): string;
}