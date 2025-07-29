<?php

use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::prefix('/admin/devices')->middleware(['auth',AuthCheck::class])->group(function () {
  Route::get('/', [AdminController::class, 'devices'])->name('admin.devices');

  Route::post('/create', [AdminController::class, 'devices_create'])->name('admin.devices.create');

  Route::put('/{id}', [AdminController::class, 'devices_update'])->name('admin.devices.update');

  Route::delete('/{id}', [AdminController::class, 'devices_delete'])->name('admin.devices.delete');
});