<?php

use App\Http\Controllers\Ecommerce\RefundController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDashboard\UserDashboardController;
use App\Http\Controllers\UserDashboard\WishlistController;
use Illuminate\Support\Facades\Route;

/**
 * User Dashboard
 */
Route::prefix('user-dashboard')->name('user-dashboard')->group(function () {
    // Recap page
    Route::get('', [UserDashboardController::class, 'index'])->name('');

    // Settings
    Route::get('/settings', [UserDashboardController::class, 'settings'])->name('.settings');

    // Order list
    Route::get('/orders', [UserDashboardController::class, 'orders'])->name('.orders');
    Route::get('/orders/download', [UserDashboardController::class, 'downloadOrders'])->name('.orders.download');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('.wishlist');
});

// Delete the account
Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');
