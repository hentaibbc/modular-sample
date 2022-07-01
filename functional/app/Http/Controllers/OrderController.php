<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Services\Linepay;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // 檢查資料
        $this->validateData($request);
        // 重新組合要寫入資料庫的內容
        $details = $this->prepareDetail($request);
        // 建立訂單資料
        $order = $this->doCreateOrder($details);
        // 付款連結
        $paymentUrl = $this->getPaymentUrl($order);

        // 回傳資料
        return [
            'orderId'      => $order['id'],
            'paymentUrl'   => $paymentUrl,
        ];
    }

    protected function validateData(Request $request): void
    {
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
    }


    protected function prepareDetail(Request $request): Collection
    {
        return $request->collect()
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
    }

    protected function doCreateOrder($details): Order
    {
        // 計算總金額
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

    protected function getPaymentUrl($order): string
    {
        return 'http://linepay?order='.$order['id'];
    }
}
