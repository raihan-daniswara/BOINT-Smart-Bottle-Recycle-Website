<?php

use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::prefix('/admin/leaderboard')->middleware(['auth',AuthCheck::class])->group(function () {
  Route::get('/', [AdminController::class, 'leaderboard'])->name('admin.leaderboard');
});