<?php

namespace Dotech\Payment\Services;

use Dotech\Payment\Drivers\Driver;

class PaymentService
{
    private Driver $driver;

    private function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public static function factory($method): self
    {
        if (!self::has($method)) {
            throw new \Exception('Payment method not exists');
        }

        return new self(app('payment.'.$method));
    }

    public static function has($method): bool
    {
        return app()->has('payment.'.$method);
    }

    public function __call($name, $arguments)
    {
        return $this->driver->{$name}(...$arguments);
    }
}