<?php

namespace Dotech\Order\Events;

use Dotech\Order\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class OrderBeforeResponse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $resp = [];
    private $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function put(string $key, $val): self
    {
        Arr::set($this->resp, $key, $val);

        return $this;
    }

    public function getResponse(): JsonResponse
    {
        return response()->json($this->resp);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
