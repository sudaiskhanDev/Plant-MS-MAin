<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MaintenanceController;

/*
|--------------------------------------------------------------------------
| Public/Admin Maintenance Routes
|--------------------------------------------------------------------------
*/

Route::prefix('maintenances')->group(function () {

    // 👇 FIX: specific routes FIRST
    Route::middleware('auth:admin')->group(function () {
        Route::get('/staff', [MaintenanceController::class, 'staffTasks']);
        Route::put('/status/{id}', [MaintenanceController::class, 'updateStatus']);
    });

    // 👇 general routes AFTER
    Route::get('/', [MaintenanceController::class, 'index']);
    Route::post('/', [MaintenanceController::class, 'store']);
    Route::get('/{id}', [MaintenanceController::class, 'show']);
    Route::put('/{id}', [MaintenanceController::class, 'update']);
    Route::delete('/{id}', [MaintenanceController::class, 'destroy']);

});
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\MaintenanceController;

// Route::prefix('maintenances')->group(function () {
//     Route::get('/', [MaintenanceController::class, 'index']);
//     Route::post('/', [MaintenanceController::class, 'store']);
//     Route::get('/{id}', [MaintenanceController::class, 'show']);
//     Route::put('/{id}', [MaintenanceController::class, 'update']);
//     Route::delete('/{id}', [MaintenanceController::class, 'destroy']);
    
// });




