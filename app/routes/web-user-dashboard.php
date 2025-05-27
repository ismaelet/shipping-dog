<?php

use App\Http\Controllers\Ecommerce\RefundController;
use App\Http\Controllers\UserDashboard\UserDashboardController;
use App\Http\Controllers\UserDashboard\WishlistController;
use Illuminate\Support\Facades\Route;

/**
 * * User Dashboard
 */
Route::prefix('user-dashboard')->name('user-dashboard')->group(function () {
	Route::get('', [UserDashboardController::class, 'index'])->name('');

	Route::get('/settings', [UserDashboardController::class, 'settings'])->name('.settings');

	Route::get('/orders', [UserDashboardController::class, 'orders'])->name('.orders');

	Route::get('/wishlist', [WishlistController::class, 'index'])->name('.wishlist');
});
