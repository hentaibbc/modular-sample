<?php

namespace Dotech\Item\Listeners;

use Dotech\Item\Repositories\ItemRepository;
use Dotech\Order\Events\OrderPrepareDetail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class OrderPrepareDetailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderPrepareDetail $event)
    {
        $data = $event->getData();

        $items = app(ItemRepository::class)
            ->getItems(collect($data['items'])->pluck('id')->all())
            ->keyBy('id')
            ;

        $items = collect($data['items'])->map(function ($item) use ($items) {
            $srcItem = Arr::get($items, $item['id']);
            if (!$srcItem) {
                return null;
            }

            return collect($srcItem->only(['name', 'price']))->merge([
                'quantity'  => $item['quantity'],
                'details'   => $srcItem,
            ]);
        })->filter();

        $event->setData('items', $items);
    }
}
