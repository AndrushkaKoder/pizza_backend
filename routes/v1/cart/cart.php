<?php

use App\Http\Controllers\Api\V1\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->controller(CartController::class)->group(function () {
    Route::get('/cart', 'getCart')->name('cart.index');
    Route::post('/cart/add/{product}', 'addToCart')->name('cart.create');
    Route::delete('/cart/delete/{cartItem}', 'delete')->name('cart.delete');
    Route::delete('/cart/decrement/{cartItem}', 'decrement')->name('cart.product.decrement');
});
