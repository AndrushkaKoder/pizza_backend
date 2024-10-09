<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

#auth
Route::controller(AuthController::class)->prefix('user')->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
});

#user routes
Route::middleware('auth:sanctum')->controller(UserController::class)->group(function () {
    Route::get('/user', 'index')->name('user.index');
    Route::put('/user/{user}', 'update')->name('user.update');
    Route::delete('/user/{user}', 'delete')->name('user.delete');
    Route::get('/user/logout', 'logout')->name('user.logout');
});

