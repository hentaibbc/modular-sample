<?php

namespace Dotech\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Dotech\Order\Events\OrderBeforeResponse;
use Dotech\Order\Events\OrderPrepareDetail;
use Dotech\Order\Events\OrderValidateRequest;
use Dotech\Order\Models\Order;
use Dotech\Order\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
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

        // 回傳資料
        return $this->response($order);
    }

    /**
     * 檢查傳入的資料
     *
     * @param Request $request
     * @return void
     */
    protected function validateData(Request $request): void
    {
        $event = new OrderValidateRequest($request);
        Event::dispatch($event);

        $errors = $event->getErrors();

        if ($errors->isNotEmpty()) {
            throw new BadRequestException('Validation failed');
        }
    }

    /**
     * 準備要寫入資料庫的內容
     *
     * @param Request $request
     * @return Collection
     */
    protected function prepareDetail(Request $request): Collection
    {
        $event = new OrderPrepareDetail($request->collect());
        Event::dispatch($event);

        return $event->getData();
    }

    /**
     * 建立訂單
     *
     * @param Collection $details
     * @return Order
     */
    protected function doCreateOrder(Collection $details): Order
    {
        return app()
            ->make(OrderRepository::class)
            ->createOrder($details);
    }

    /**
     * 回傳資料
     *
     * @param Order $order
     * @return JsonResponse
     */
    protected function response(Order $order): JsonResponse
    {
        $event = new OrderBeforeResponse($order);
        Event::dispatch($event);
        $addition = $event->getResponse()->getData();

        return response()
            ->json(
                collect([
                    'orderId'   => $order->id,
                ])->merge($addition)
            )
            ;
    }
}
