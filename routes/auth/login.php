<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_post'])->name('login.post');
});

Route::middleware('auth')->group(function(){
  Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
  Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});