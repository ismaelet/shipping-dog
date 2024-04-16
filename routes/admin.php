<?php

use App\Http\Controllers\Admin\Pages\DashboardController;
use App\Http\Controllers\Admin\Resources\AdminCategoryController;
use App\Http\Controllers\Admin\Resources\NewsletterSubscriberController;
use App\Http\Controllers\Admin\Resources\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
	// Admin dashboard home
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::post('/dashboard', [DashboardController::class, 'update']);

	// Users
	Route::resource('users', UserController::class)
		->only(['index', 'edit', 'update', 'destroy']);

	// Newsletters
	Route::resource('newsletter-subscribers', NewsletterSubscriberController::class)
		->only(['index', 'destroy']);

	// Settings - Categories
	Route::resource('admin-categories', AdminCategoryController::class);
});
