<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('/redeems')->middleware(['auth'])->group(function () {
  Route::get('/', [UserController::class, 'redeems'])->name('redeems');
   Route::post('/{id}', [UserController::class, 'redeems_redeem'])->name('user.redeems.redeem');
});