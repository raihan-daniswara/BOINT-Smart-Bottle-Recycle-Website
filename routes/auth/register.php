<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::prefix('/register')->middleware('guest')->group(function(){
  Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
  Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name(name: 'verify.otp');
  Route::get('/', [AuthController::class, 'register'])->name('register');
  Route::post('/', [AuthController::class, 'register_post'])->name('register.post');
});

Route::middleware('guest')->get('/', function (){
  return view('welcome');
})->name('welcome');