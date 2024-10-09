<?php

use App\Http\Controllers\Api\V1\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/{product}', [CartController::class, 'addToCart']);
    Route::delete('/cart/{cartItem}', [CartController::class, 'delete']);
    Route::delete('/cart/{cartItem}/decrement',[CartController::class, 'decrement']);
});
