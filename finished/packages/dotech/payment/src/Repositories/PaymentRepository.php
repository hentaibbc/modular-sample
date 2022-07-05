<?php

namespace Dotech\Payment\Repositories;

use Dotech\Order\Models\Order;
use Dotech\Payment\Services\PaymentService;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PaymentRepository
{
    public function validate(Request $request): ?MessageBag
    {
        $validator = Validator::make($request->all(), [
            'payment.id'        => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!PaymentService::has($value)) {
                        $fail($attribute.' not exists');
                    }
                },
            ],
        ]);

        return $validator->fails() ? $validator->errors() : null;
    }

    public function getPaymentUrl(Order $order): string
    {
        return PaymentService::factory($order->getDetail('payment.id'))->getPaymentUrl($order);
    }
}