<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('/devices')->middleware(['auth'])->group(function () {
  Route::get('/', [UserController::class, 'devices'])->name('devices');
});