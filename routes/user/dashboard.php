<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('/dashboard')->middleware(['auth'])->group(function () {
  Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');
  Route::post('/scan-result', [UserController::class, 'scan_store']);
});