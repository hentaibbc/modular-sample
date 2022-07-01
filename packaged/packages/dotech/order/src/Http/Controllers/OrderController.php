<?php

namespace Dotech\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Dotech\Item\Repositories\ItemRepository;
use Dotech\Order\Models\Order;
use Dotech\Order\Repositories\OrderRepository;
use Dotech\Payment\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
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
        $errors = collect([
            OrderRepository::class,
            ItemRepository::class,
            PaymentRepository::class,
        ])->reduce(function ($bag, $class) use ($request) {
            $error = app()->make($class)->validate($request);

            if (!$error) {
                return $bag;
            }
            return $bag->merge($error);
        }, new MessageBag());

        if ($errors->isNotEmpty()) {
            throw new BadRequestException('Validation failed');
        }
    }

    protected function prepareDetail(Request $request): Collection
    {
        $collect = $request->collect();
        $items = collect($collect['items']);
        $srcItems = app()
            ->make(ItemRepository::class)
            ->getItems($items->pluck('id')->all())
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
    }

    protected function doCreateOrder($details): Order
    {
        return app()->make(OrderRepository::class)->createOrder($details);
    }

    protected function getPaymentUrl($order): string
    {
        return app()->make(PaymentRepository::class)->getPaymentUrl($order);
    }
}
