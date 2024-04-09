<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Users\UserListController;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('admin')->middleware('admin')->namespace('Admin')->group(function () {
	// Admin dashboard home
	Route::get('/', [HomeController::class, 'index'])->name('admin');
	Route::post('/', [HomeController::class, 'update']);

	// Admin dashboard sections
	Route::get('/users/list', [UserListController::class, 'index'])->name('admin.users.list');
	Route::post('/users/list', [UserListController::class, 'update']);
});
