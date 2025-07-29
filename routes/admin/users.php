<?php

use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::prefix('/admin/users')->middleware(['auth',AuthCheck::class])->group(function () {
  Route::get('/', [AdminController::class, 'users'])->name('admin.users');
  Route::get('/detail', [AdminController::class, 'users_detail'])->name('admin.users.detail');

  Route::post('/create', [AdminController::class, 'users_create'])->name('admin.users.create');

  Route::put('/{id}', [AdminController::class, 'users_update'])->name('admin.users.update');

  Route::delete('/{id}', [AdminController::class, 'users_delete'])->name('admin.users.delete');
});