
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

Route::prefix('notifications')->group(function () {

    // 🔥 STAFF ONLY ROUTES (LOGIN REQUIRED)
    Route::middleware('auth:admin')->group(function () {

        Route::get('/my', [NotificationController::class, 'myNotifications']);

    });
    // 🔥 ADMIN CRUD (as it is - keep this)
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/', [NotificationController::class, 'store']);
    Route::get('/{id}', [NotificationController::class, 'show']);
    Route::put('/{id}', [NotificationController::class, 'update']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);


});
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\NotificationController;

// Route::prefix('notifications')->group(function () {
//     Route::get('/', [NotificationController::class, 'index']);
//     Route::post('/', [NotificationController::class, 'store']);
//     Route::get('/{id}', [NotificationController::class, 'show']);
//     Route::put('/{id}', [NotificationController::class, 'update']);
//     Route::delete('/{id}', [NotificationController::class, 'destroy']);
// });