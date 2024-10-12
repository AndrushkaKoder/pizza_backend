<?php

use Illuminate\Support\Facades\Route;

#api v1 routes
Route::prefix('v1')->group(function () {
    include 'v1/user/user.php';
    include 'v1/cart/cart.php';
    include 'v1/products/products.php';
    include 'v1/orders/orders.php';
});

