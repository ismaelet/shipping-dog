<?php

use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Ecommerce\OrderController;
use App\Http\Controllers\Ecommerce\OrderItemController;
use App\Http\Controllers\Ecommerce\PaymentRequestController;
use App\Http\Controllers\Ecommerce\ProductController;
use App\Http\Controllers\Ecommerce\RefundController;
use App\Http\Controllers\Ecommerce\SaleController;
use App\Http\Controllers\Ecommerce\SellerController;
use App\Http\Controllers\Ecommerce\SellerDashboard\PortfolioController;
use App\Http\Controllers\Ecommerce\ShopController;
use App\Http\Controllers\SellerDashboard\SellerDashboardController;
use App\Http\Middleware\Cart;
use App\Http\Middleware\EmptyCart;
use App\Http\Middleware\OrderRecap;
use App\Http\Middleware\Seller;
use Illuminate\Support\Facades\Route;

/**
 * * Shop
 */
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/artist/{seller}', [SellerController::class, 'index'])->name('seller.show');

// Seller dashboard
Route::prefix('/manage')->middleware(Seller::class)->name('seller-dashboard')->group(function () {
	Route::any('', [SellerDashboardController::class, 'index'])->name('');
	Route::any('/portfolio', [PortfolioController::class, 'index'])->name('.portfolio');
	Route::any('/portfolio/add', [PortfolioController::class, 'add'])->name('.portfolio.add');
	Route::any('/sales/prices', [PortfolioController::class, 'prices'])->name('.prices');
	// Route::any('/sales/list', LegacyController::class)->name('.sales');
});

/**
 * * Product
 */
Route::resource('products', ProductController::class)->only(['show', 'update'])->withTrashed();

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

	// // Cart step: Address
	// Route::any('/address', LegacyController::class)->name('address');

	// // Cart step: Offers
	// Route::any('/offers', LegacyController::class)->name('offers');

	// // Cart step: Payment
	// Route::any('/payment', LegacyController::class)->name('payment');
});

/**
 * * Order
 */
// Find an order
Route::get('orders/find', [OrderController::class, 'find'])->name('orders.find');

// Order resource routes
Route::resource('orders', OrderController::class)->middleware(OrderRecap::class)->only(['show', 'destroy']);
Route::resource('orders', OrderController::class)->only(['update']);

Route::any('/refund', [RefundController::class, 'index'])->name('refund');
