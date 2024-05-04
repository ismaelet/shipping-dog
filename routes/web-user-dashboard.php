<?php

use Illuminate\Support\Facades\Route;

/**
 * * User Dashboard
 */
Route::prefix('user-dashboard')->name('user-dashboard.')->group(function () {
	Route::get('/orders', function () {
		return view('user-dashboard.orders');
	})->name('orders');
});
