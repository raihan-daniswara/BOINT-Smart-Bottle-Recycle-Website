<?php

use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::prefix('/admin/submissions')->middleware(['auth',AuthCheck::class])->group(function () {
  Route::get('/', [AdminController::class, 'submissions'])->name('admin.submissions');
  
  Route::get('/detail', [AdminController::class, 'submissions_detail'])->name('admin.submissions.detail');

  Route::post('/create/submissions', [AdminController::class, 'submissions_create'])->name('admin.submissions.create');
  Route::post('/create/redeems', [AdminController::class, 'redeems_create'])->name('admin.redeems.create');

  
  Route::put('/{id}/{status}', [AdminController::class, 'submissions_update_status'])->name('admin.submissions.update.status');

});