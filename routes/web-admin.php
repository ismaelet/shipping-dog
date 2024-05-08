<?php

use App\Http\Controllers\Admin\Pages\DashboardController;
use App\Http\Controllers\Admin\Pages\LabelGeneratorController;
use App\Http\Controllers\Admin\Pages\OrderLogController;
use App\Http\Controllers\Admin\Pages\ProductLogController;
use App\Http\Controllers\Admin\Resources\OrderController;
use App\Http\Controllers\Admin\Resources\DesignController;
use App\Http\Controllers\Admin\Resources\SellerBalanceController;
use App\Http\Controllers\Admin\Resources\SellerController;
use App\Http\Controllers\Admin\Resources\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('admin')->name('admin.')->group(function () {
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
	Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
	Route::post('/users/{id}/login', [UserController::class, 'login'])->name('users.login');

	// Sellers
	Route::resource('sellers', SellerController::class)->only(['index']);
	Route::resource('seller-balances', SellerBalanceController::class)->only(['index']);

	// Designs
	Route::resource('designs', DesignController::class)->only(['index']);

	// Orders
	Route::resource('orders', OrderController::class)->only(['index', 'edit', 'update']);
	Route::post('/orders/{id}/resend-email', [OrderController::class, 'resendEmail'])->name('orders.resend-email');

	/**
	 * * Custom routes
	 */
	// Logs
	Route::get('log-orders', [OrderLogController::class, 'index'])->name('log-orders');
	Route::post('log-orders', [OrderLogController::class, 'update']);
	Route::get('log-products', [ProductLogController::class, 'index'])->name('log-products');
	Route::post('log-products', [ProductLogController::class, 'update']);

	// Labels
	Route::get('labels/generate', [LabelGeneratorController::class, 'index'])->name('labels.generate');
	Route::post('labels/generate', [LabelGeneratorController::class, 'generate']);
});
