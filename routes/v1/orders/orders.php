<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OrdersController;

Route::middleware('auth:sanctum')->controller(OrdersController::class)->group(function () {
    Route::get('/orders', 'index')->name('orders.index');
    Route::get('/orders/{order}', 'show')->name('order.show');
    Route::post('/order', 'create')->name('order.create');
    Route::get('/order/{order}/change_status/{statusId}', 'changeStatus')->name('order.change_status');
    Route::get('/order/{order}/change_payment/{paymentId}', 'changePayment')->name('order.change_payment');
});

