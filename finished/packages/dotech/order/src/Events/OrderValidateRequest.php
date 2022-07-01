<?php

namespace Dotech\Order\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\MessageBag;

class OrderValidateRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $request;
    private $errors;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->errors = new MessageBag();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function pushErrors(?MessageBag $error): self
    {
        if ($error instanceof MessageBag) {
            $this->errors->merge($error);
        }

        return $this;
    }

    public function getErrors(): MessageBag
    {
        return $this->errors;
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
