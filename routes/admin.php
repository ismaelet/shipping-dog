<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Users\UserFormController;
use App\Http\Controllers\Admin\Users\UserListController;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('admin')->middleware('admin')->namespace('Admin')->group(function () {
	// Admin dashboard home
	Route::get('/', [HomeController::class, 'index'])->name('admin');
	Route::post('/', [HomeController::class, 'update']);

	// Users
	Route::get('/users/list', [UserListController::class, 'index'])->name('admin.users.list');
	Route::post('/users/list', [UserListController::class, 'update']);

	Route::get('/users/add', [UserFormController::class, 'index'])->name('admin.users.add');
	Route::post('/users/add', [UserFormController::class, 'update']);
	Route::get('/users/edit/{any}', [UserFormController::class, 'index'])->where('any', '.*')->name('admin.users.edit');
	Route::post('/users/edit/{any}', [UserFormController::class, 'update'])->where('any', '.*');
});
