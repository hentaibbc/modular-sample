<?php

namespace Dotech\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $cast = [
        'details'   => 'array',
    ];

    public function getDetail($key, $default = null)
    {
        return Arr::get($this->attributes, $key, $default);
    }
}
