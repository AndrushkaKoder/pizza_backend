<?php

use App\Http\Controllers\Api\ProductsController;
use Illuminate\Support\Facades\Route;

include 'user/user.php';

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ProductsController::class, 'index'])->name('main.index');
});

