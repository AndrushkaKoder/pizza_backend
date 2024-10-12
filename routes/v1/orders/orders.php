<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OrdersController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::post('/order', [OrdersController::class, 'create'])->name('orders.create');
    Route::get('/order/{order}/change_status/{statusId}', [OrdersController::class, 'changeStatus'])->name('orders.change_status');
    Route::get('/order/{order}/change_payment/{paymentId}', [OrdersController::class, 'changePayment'])->name('orders.change_payment');
});

