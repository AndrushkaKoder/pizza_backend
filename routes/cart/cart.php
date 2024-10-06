<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/{product}', [CartController::class, 'addToCart']);
    Route::delete('/cart/{cartItem}', [CartController::class, 'delete']);
    Route::get('/cart/{cartItem}/decrement',[CartController::class, 'decrement']);
});
