<?php

namespace Dotech\Payment\Repositories;

use Dotech\Order\Models\Order;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentRepository
{
    public function validate(Request $request): ?MessageBag
    {
        $validator = Validator::make($request->all(), [
            'payment.id'        => 'required|in:linepay,newebpay',
        ]);

        return $validator->fails() ? $validator->errors() : null;
    }

    public function getPaymentUrl(Order $order): string
    {
        return 'http://linepay?order='.$order['id'];
    }
}