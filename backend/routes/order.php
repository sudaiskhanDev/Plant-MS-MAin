<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;


Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});



// Route::middleware('auth:user_api')->prefix('orders')->group(function () {

//     Route::get('/', [OrderController::class, 'index']);
//     Route::post('/', [OrderController::class, 'store']);
//     Route::get('/{id}', [OrderController::class, 'show']);
//     Route::delete('/{id}', [OrderController::class, 'destroy']);

// });