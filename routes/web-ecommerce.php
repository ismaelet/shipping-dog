<?php

use App\Http\Controllers\Api\ProductTypeController;
use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Ecommerce\OrderController;
use App\Http\Controllers\Ecommerce\OrderItemController;
use App\Http\Controllers\Ecommerce\ProductController;
use App\Http\Controllers\LegacyController;
use App\Http\Middleware\Cart;
use App\Http\Middleware\EmptyCart;
use App\Http\Middleware\OrderRecap;
use Illuminate\Support\Facades\Route;

/**
 * * Shop
 */
Route::any('/shop', LegacyController::class)->name('shop');

/**
 * * Product
 */
Route::resource('products', ProductController::class)->only(['show', 'update']);

Route::post('/products/{product}/status', [ProductController::class, 'changeStatus'])->name('products.status');

/**
 * * Order Item
 */
// Create an order
Route::resource('order-items', OrderItemController::class)->only(['show', 'store']);

/**
 * * Cart
 */
// General cart routes
Route::get('/cart', function () {
	$orderItemstep = cart()?->step;

	if (!$orderItemstep) {
		return redirect()->route('cart.empty');
	}

	if (in_array($orderItemstep, ['access'])) {
		return redirect()->route('cart.' . $orderItemstep);
	}

	// ! Legacy support
	return redirect('/cart/' . $orderItemstep);
})->name('cart');

// Empty cart
Route::get('/cart/empty', function () {
	return view('ecommerce.cart.empty');
})->middleware(EmptyCart::class)->name('cart.empty');

// Cart steps
Route::prefix('cart')->middleware(Cart::class)->name('cart.')->group(function () {
	// Cart step: Access
	Route::get('/access', [CartController::class, 'indexAccess'])->name('access');
	Route::post('/access/guest', [CartController::class, 'guestLogin'])->name('access.guest');

	// Cart step: Address
	Route::any('/address', LegacyController::class)->name('address');

	// Cart step: Offers
	Route::any('/offers', LegacyController::class)->name('offers');

	// Cart step: Payment
	Route::any('/payment', LegacyController::class)->name('payment');
});

/**
 * * Order
 */
// Order resource routes
Route::resource('orders', OrderController::class)->middleware(OrderRecap::class)->only(['show', 'destroy']);

// Find an order
Route::get('/orders/find', [OrderController::class, 'find'])->name('orders.find');
