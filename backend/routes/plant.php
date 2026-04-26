<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlantController;

Route::prefix('plants')->group(function () {
    Route::get('/', [PlantController::class, 'index']);
    Route::post('/', [PlantController::class, 'store']);
    Route::get('/{id}', [PlantController::class, 'show']);
    Route::put('/{id}', [PlantController::class, 'update']);
    Route::delete('/{id}', [PlantController::class, 'destroy']);
    Route::put('/plants/{id}/stock', [PlantController::class, 'updateStock']);
});