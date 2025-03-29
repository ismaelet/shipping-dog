<?php

use App\Http\Controllers\Services\Gls\GlsController;
use App\Http\Controllers\Services\PayPal\PayPalController;
use App\Http\Middleware\UnconfirmedOrder;
use Illuminate\Support\Facades\Route;

/**
 * * PayPal
 */
Route::prefix('paypal')->middleware(UnconfirmedOrder::class)->name('paypal.')->group(function () {
	Route::resource('orders', PayPalController::class)->only(['create']);
	Route::get('/orders/{order}/capture', [PayPalController::class, 'capture'])->name('order.capture');
	Route::get('/orders/{order}/cancel', [PayPalController::class, 'cancel'])->name('order.cancel');
});

/**
 * * GLS
 */
Route::prefix('gls')->middleware('admin')->name('gls.')->group(function () {
	Route::post('/transmit', [GlsController::class, 'transmitParcels'])->name('transmit');
});
