<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderDetailController;

Route::prefix('order-details')->group(function () {
    Route::get('/', [OrderDetailController::class, 'index']);
    Route::post('/', [OrderDetailController::class, 'store']);
    Route::get('/{id}', [OrderDetailController::class, 'show']);
    Route::put('/{id}', [OrderDetailController::class, 'update']);
    Route::delete('/{id}', [OrderDetailController::class, 'destroy']);
});