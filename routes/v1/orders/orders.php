<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OrdersController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/order', [OrdersController::class, 'create'])->name('orders.create');
});

