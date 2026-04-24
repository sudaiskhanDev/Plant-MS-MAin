<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;

Route::prefix('cart')->group(function () {

    Route::get('/', [CartController::class, 'index']);
    Route::post('/', [CartController::class, 'store']);
    Route::put('/{id}', [CartController::class, 'update']);
    Route::delete('/{id}', [CartController::class, 'destroy']);
    Route::delete('/clear/all', [CartController::class, 'clear']);

});