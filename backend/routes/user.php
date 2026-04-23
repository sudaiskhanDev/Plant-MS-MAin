<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;

Route::prefix('user')->group(function () {

    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);

    Route::middleware('auth:user_api')->group(function () {
        Route::get('/me', [UserAuthController::class, 'me']);
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });

});