<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::prefix('devices')->group(function () {
    Route::get('{device}/pair-url', [DeviceController::class, 'generateForDevice']);
    Route::get('{device}/pairing-status', [DeviceController::class, 'checkStatus']);
    Route::post('{device}/submit-items', [DeviceController::class, 'submit']);
    Route::put('{device}/maintenance', [DeviceController::class, 'setMaintenance']);
    Route::post('{device}/status', [DeviceController::class, 'updateStatus']);
    Route::get('{device}/status', [DeviceController::class, 'getStatus']);
});
Route::post('pair/confirm', [DeviceController::class, 'confirm']);
Route::get('users/{userId}/stats', [DeviceController::class, 'getUserStats']);
