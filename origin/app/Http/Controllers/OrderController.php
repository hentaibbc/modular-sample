<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Services\Linepay;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // 檢查資料
        $validator = Validator::make($request->all(), [
            'items.*.id'        => 'required|exists:items,id',
            'items.*.quantity'  => 'required|integer|min:1',
            'buyer.name'        => 'required',
            'buyer.phone'       => 'required',
            'payment.id'        => 'required|in:linepay,newebpay',
        ]);

        if ($validator->fails()) {
            throw new BadRequestException('Validation failed');
        }

        // 重新組合要寫入資料庫的內容
        $details = $request->collect()
            ->pipe(function ($collect) {
                $items = collect($collect['items']);
                $srcItems = Item::query()
                    ->whereIn('id', $items->pluck('id'))
                    ->get()
                    ->keyBy('id')
                    ;

                return $collect->merge([
                    'items' => $items->map(function ($item) use ($srcItems) {
                        $srcItem = Arr::get($srcItems, $item['id']);
                        if (!$srcItem) {
                            return null;
                        }

                        return collect($srcItem->only(['name', 'price']))->merge([
                            'quantity'  => $item['quantity'],
                            'details'   => $srcItem,
                        ]);
                    })->filter(),
                ]);
            })
            ;

        $total = collect($details['items'])->reduce(function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);

        // 寫入資料庫
        $order = Order::create([
            'details'   => $details,
            'total'     => $total,
        ]);

        // 付款連結
        $paymentUrl = 'http://linepay?order='.$order['id'];

        // 回傳
        return [
            'orderId'      => $order['id'],
            'paymentUrl'   => $paymentUrl,
        ];
    }
}
