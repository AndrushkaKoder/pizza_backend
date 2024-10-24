<?php

use App\Http\Controllers\Api\V1\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.store');
    Route::delete('/cart/delete/{cartItem}', [CartController::class, 'delete'])->name('cart.delete');
    Route::delete('/cart/{cartItem}/decrement',[CartController::class, 'decrement'])->name('cart.product.decrement');
});
