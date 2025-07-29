<?php

use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::prefix('/admin/rewards')->middleware(['auth',AuthCheck::class])->group(function () {
  Route::get('/', [AdminController::class, 'rewards'])->name('admin.rewards');
  Route::post('/create', [AdminController::class, 'rewards_create'])->name('admin.rewards.create');
  Route::put('/{id}', [AdminController::class, 'rewards_update'])->name('admin.rewards.update');
  Route::delete('/{id}', [AdminController::class, 'rewards_delete'])->name('admin.rewards.delete');
});