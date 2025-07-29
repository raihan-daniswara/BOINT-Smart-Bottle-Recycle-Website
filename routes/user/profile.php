<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('/profile')->middleware(['auth'])->group(function () {
  Route::get('/', [UserController::class, 'profile'])->name('profile');
  Route::put('/{id}', [UserController::class, 'profile_update'])->name('profile.update');
});