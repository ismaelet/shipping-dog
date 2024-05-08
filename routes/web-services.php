<?php

use App\Http\Controllers\Services\PayPal\PayPalOrderController;
use Illuminate\Support\Facades\Route;

/**
 * * PayPal
 */
Route::prefix('paypal')->name('paypal.')->group(function () {
	Route::resource('orders', PayPalOrderController::class)->only(['create']);

	Route::get('/orders/{order}/success', [PayPalOrderController::class, 'success'])->name('order.success');

	Route::get('/orders/{order}/reopen', [PayPalOrderController::class, 'reopen'])->name('order.reopen');
});

/**
 * * GLS
 */
Route::prefix('gls')->name('gls.')->group(function () {
	Route::post('/add-shipments', [PayPalOrderController::class, 'addShipments'])->name('add-shipments');
});
