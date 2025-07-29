<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('/rewards')->middleware(['auth'])->group(function () {
  Route::get('/', [UserController::class, 'rewards'])->name('rewards');
   Route::post('/{id}', [UserController::class, 'rewards_redeem'])->name('user.rewards.redeem');
});