<?php

namespace Dotech\Order\Repositories;

use Dotech\Order\Models\Order;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class OrderRepository
{
    public function validate(Request $request): ?MessageBag
    {
        $validator = Validator::make($request->all(), [
            'items.*.quantity'  => 'required|integer|min:1',
            'buyer.name'        => 'required',
            'buyer.phone'       => 'required',
        ]);

        return $validator->fails() ? $validator->errors() : null;
    }

    public function createOrder(Collection $details)
    {
        $total = $this->calculateTotal($details);

        return Order::create([
            'details'   => $details,
            'total'     => $total,
        ]);
    }

    protected function calculateTotal(Collection $details): int
    {
        return collect($details['items'])->reduce(function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
}