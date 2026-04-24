<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MaintenanceController;

Route::prefix('maintenance')->group(function () {

    Route::get('/', [MaintenanceController::class, 'index']);
    Route::post('/', [MaintenanceController::class, 'store']);
    Route::get('/{id}', [MaintenanceController::class, 'show']);
    Route::put('/{id}', [MaintenanceController::class, 'update']);
    Route::delete('/{id}', [MaintenanceController::class, 'destroy']);

});