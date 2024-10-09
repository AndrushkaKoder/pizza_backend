<?php

use App\Http\Controllers\Api\V1\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
