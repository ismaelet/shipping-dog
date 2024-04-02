<?php

use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('admin')->middleware('admin')->namespace('Admin')->group(function () {
	Route::get('', function () {
		return view('/admin/home')->with('title', 'Dashboard');
	})->name('admin');

	Route::get('shipping/courier', function () {
		return redirect('/public/legacy/admin/shipping/courier');
	})->name('admin.shipping.courier');
});
