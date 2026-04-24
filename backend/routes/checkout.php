<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CheckoutController;

Route::middleware('auth:api')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
});