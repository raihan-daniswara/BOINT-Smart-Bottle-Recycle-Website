<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('/submissions')->middleware(['auth'])->group(function () {
  Route::get('/', [UserController::class, 'submissions'])->name('submissions');
});