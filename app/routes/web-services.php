<?php

use App\Http\Controllers\Services\Nexi\NexiController;
use App\Http\Controllers\Services\PayPal\PayPalController;
use App\Http\Controllers\Services\PayPal\PayPalExchangeController;
use App\Http\Middleware\UnconfirmedOrder;
use Illuminate\Support\Facades\Route;

/**
 * PayPal
 */
Route::prefix('paypal')->name('paypal.')->group(function () {
    Route::middleware(UnconfirmedOrder::class)->group(function () {
        // Order purchase
        Route::resource('orders', PayPalController::class)->only(['create']);

        Route::get('/orders/{order}/capture', [PayPalController::class, 'capture'])->name('order.capture');

        Route::get('/orders/{order}/cancel', [PayPalController::class, 'cancel'])->name('order.cancel');
    });

    // Payment of shipping fees for exchanges
    Route::get('/exchange/{changeRequest}/create', [PayPalExchangeController::class, 'create'])->name('exchange.create');

    Route::get('/exchange/{changeRequest}/capture', [PayPalExchangeController::class, 'capture'])->name('exchange.capture');

    Route::get('/exchange/{changeRequest}/cancel', [PayPalExchangeController::class, 'cancel'])->name('exchange.cancel');
});

/**
 * Nexi payment routes for orders
 */
Route::prefix('services/nexi')
    ->name('nexi.orders.')
    ->group(function () {
        Route::get('/create/{order}', [
            NexiController::class, 'create',
        ])->name('create');
    });

Route::get('/services/nexi/result/{order}', [
    NexiController::class, 'result',
])->name('nexi.orders.result');

Route::get('/services/nexi/cancel/{order}', [
    NexiController::class, 'cancel',
])->name('nexi.orders.cancel');

Route::post('/services/nexi/notification', [
    NexiController::class, 'notification',
])->name('nexi.orders.notification');
