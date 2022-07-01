<?php

use Dotech\Order\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'    => '/api',
], function () {
    Route::post('/order/create', [OrderController::class, 'createOrder']);
});