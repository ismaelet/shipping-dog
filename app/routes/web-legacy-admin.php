<?php

use App\Http\Controllers\Admin\Pages\DashboardController;
use App\Http\Controllers\Admin\Pages\OrderLogController;
use App\Http\Controllers\Admin\Pages\ProductLogController;
use App\Http\Controllers\Admin\Resources\OrderController;
use App\Http\Controllers\Admin\Resources\DesignController;
use App\Http\Controllers\Admin\Resources\ProductController;
use App\Http\Controllers\Admin\Resources\SellerBalanceController;
use App\Http\Controllers\Admin\Resources\SellerController;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('legacy-admin')->name('legacy-admin.')->group(function () {

	/**
	 * * Dashboard
	 */
	// Admin dashboard home
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::post('/dashboard', [DashboardController::class, 'update']);

	/**
	 * * Resources
	 */
	// Sellers
	Route::resource('sellers', SellerController::class)->only(['index']);
	Route::resource('seller-balances', SellerBalanceController::class)->only(['index']);

	// Designs
	Route::resource('designs', DesignController::class)->only(['index']);

	// Products
	Route::resource('products', ProductController::class)->only(['index']);

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
});
