<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('/leaderboard')->middleware(['auth'])->group(function () {
  Route::get('/', [UserController::class, 'leaderboard'])->name('leaderboard');
});