<?php

namespace Dotech\Payment\Repositories;

use Dotech\Order\Models\Order;
use Dotech\Payment\Services\Contracts\PaymentService;
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
                    try {
                        $this->getPaymentService($value);
                    } catch (Throwable $e) {
                        app(ExceptionHandler::class)->report($e);

                        $fail($attribute.' not exists');
                    }
                },
            ],
        ]);

        return $validator->fails() ? $validator->errors() : null;
    }

    public function getPaymentUrl(Order $order): string
    {
        return $this
            ->getPaymentService($order->getDetail('payment.id'))
            ->getPaymentUrl($order)
            ;
    }

    protected function getPaymentService($name): PaymentService
    {
        return app('payment.'.$name);
    }
}