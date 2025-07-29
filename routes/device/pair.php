<?php

use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::middleware('auth')->group(function () {
    Route::get('/pair/confirm', [DeviceController::class, 'webConfirm']);
    Route::post('/pair/confirm', [DeviceController::class, 'webConfirmSubmit']);
});