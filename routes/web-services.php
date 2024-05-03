<?php

use App\Http\Controllers\Ecommerce\PayPal\PayPalOrderController;
use Illuminate\Support\Facades\Route;

/**
 * * PayPal
 */
Route::prefix('paypal')->name('paypal.')->group(function () {
	Route::resource('orders', PayPalOrderController::class)->only(['create']);

	Route::get('/orders/{order}/success', [PayPalOrderController::class, 'success'])->name('order.success');

	Route::get('/orders/{order}/reopen', [PayPalOrderController::class, 'reopen'])->name('order.reopen');
});
