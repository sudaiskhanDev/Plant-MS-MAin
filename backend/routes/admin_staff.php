<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
});

// Protected routes (JWT required)
Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


// use App\Http\Controllers\Api\AdminStaffController;

// Route::prefix('admin-staff')->group(function () {
//     Route::get('/', [AdminStaffController::class, 'index']);
    
// });

use App\Http\Controllers\Api\AdminStaffController;

Route::prefix('admin-staff')->group(function () {
    Route::get('/', [AdminStaffController::class, 'index']);

    // ✅ ADD THIS
    Route::delete('/{id}', [AdminStaffController::class, 'destroy']);
});