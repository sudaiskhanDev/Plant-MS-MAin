<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupplierOrderController;

Route::prefix('supplier-orders')->group(function () {
    Route::get('/', [SupplierOrderController::class, 'index']);
    Route::post('/', [SupplierOrderController::class, 'store']);
    Route::get('/{id}', [SupplierOrderController::class, 'show']);
    Route::put('/{id}', [SupplierOrderController::class, 'update']);
    Route::delete('/{id}', [SupplierOrderController::class, 'destroy']);
});