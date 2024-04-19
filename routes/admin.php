<?php

use App\Http\Controllers\Admin\Pages\DashboardController;
use App\Http\Controllers\Admin\Pages\LabelGeneratorController;
use App\Http\Controllers\Admin\Pages\OrderLogController;
use App\Http\Controllers\Admin\Pages\ProductLogController;
use App\Http\Controllers\Admin\Resources\AdminCategoryController;
use App\Http\Controllers\Admin\Resources\NewsletterSubscriberController;
use App\Http\Controllers\Admin\Resources\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
	/**
	 * * Dashboard
	 */
	// Admin dashboard home
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::post('/dashboard', [DashboardController::class, 'update']);

	/**
	 * * Resources
	 */
	// Users
	Route::resource('users', UserController::class)
		->only(['index', 'edit', 'update', 'destroy']);

	// Newsletters
	Route::resource('newsletter-subscribers', NewsletterSubscriberController::class)
		->only(['index', 'destroy']);

	// Settings - Categories
	Route::resource('admin-categories', AdminCategoryController::class);

	/**
	 * * Custom routes
	 */
	// Logs
	Route::get('log-orders', [OrderLogController::class, 'index'])->name('log-orders');
	Route::get('log-products', [ProductLogController::class, 'index'])->name('log-products');
	Route::post('log-products', [ProductLogController::class, 'update']);

	// Labels
	Route::get('labels/generate', [LabelGeneratorController::class, 'index'])->name('labels.generate');
	Route::post('labels/generate', [LabelGeneratorController::class, 'generate']);
});
