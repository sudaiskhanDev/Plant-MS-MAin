<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportController;

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index']);
    Route::post('/', [ReportController::class, 'store']);
    Route::get('/{id}', [ReportController::class, 'show']);
    Route::put('/{id}', [ReportController::class, 'update']);
    Route::delete('/{id}', [ReportController::class, 'destroy']);
});