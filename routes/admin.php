<?php

use App\Http\Controllers\Admin\HomeController;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('admin')->middleware('admin')->namespace('Admin')->group(function () {
	// Admin dashboard home
	Route::get('/', [HomeController::class, 'index'])->name('admin');
	Route::post('/', [HomeController::class, 'update']);
});
