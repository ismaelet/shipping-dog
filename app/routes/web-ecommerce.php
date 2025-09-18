<?php

use App\Http\Controllers\Cart\Payments\FreeController;
use App\Http\Controllers\Ecommerce\Cart\CartAccessController;
use App\Http\Controllers\Ecommerce\Cart\CartAddressController;
use App\Http\Controllers\Ecommerce\Cart\CartPaymentController;
use App\Http\Controllers\Ecommerce\Cart\CartPromotionsController;
use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Ecommerce\Order\OrderFindController;
use App\Http\Controllers\Ecommerce\OrderController;
use App\Http\Controllers\Ecommerce\OrderItemController;
use App\Http\Controllers\Ecommerce\ProductController;
use App\Http\Controllers\Ecommerce\RefundController;
use App\Http\Controllers\Ecommerce\SellerController;
use App\Http\Controllers\Ecommerce\ShopController;
use App\Http\Controllers\SearchController;
use App\Http\Middleware\Cart;
use App\Http\Middleware\EmptyCart;
use App\Http\Middleware\HasChangeRequestAccess;
use App\Http\Middleware\OrderRecap;
use App\Http\Middleware\Refund;
use App\Http\Middleware\RefundSkip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * * Search
 */
Route::get('/search', [SearchController::class, 'index'])->name('search');

/**
 * * Shop
 */
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/artist', function () {
	$seller = Auth::user()?->seller;

	if ($seller) {
		return redirect()->route('seller.show', ['seller' => $seller]);
	} else {
		return redirect()->route('shop');
	}
});

Route::get('/artist/{seller}', [SellerController::class, 'index'])->name('seller.show');

/**
 * * Product
 */
Route::resource('products', ProductController::class)->only(['show', 'update'])->withTrashed();

Route::post('/products/{product}/status', [ProductController::class, 'changeStatus'])->name('products.status');


/**
 * * Cart
 */
Route::prefix('cart')->name('cart')->group(function () {
	// Redirect to the current cart step
	Route::get('', [CartController::class, 'index'])->middleware(Cart::class)->name('');

	// Remove an item from the cart
	Route::post('/remove/{orderItem}', [CartController::class, 'removeOrderItem'])->name('.remove');

	// Cart step: Access
	Route::get('/access', [CartAccessController::class, 'index'])->middleware(Cart::class)->name('.access');
	Route::post('/access/guest', [CartAccessController::class, 'guestLogin'])->name('.access.guest');

	// Cart step: Address
	Route::get('/address', [CartAddressController::class, 'index'])->middleware(Cart::class)->name('.address');
	Route::post('/address', [CartAddressController::class, 'update'])->middleware(Cart::class)->name('.address');

	// Cart step: Promotions
	Route::get('/promotions', [CartPromotionsController::class, 'index'])->middleware(Cart::class)->name('.promotions');

	// Cart step: Payment
	Route::get('/payment', [CartPaymentController::class, 'index'])->middleware(Cart::class)->name('.payment');
	Route::post('/payment', [CartPaymentController::class, 'update'])->middleware(Cart::class)->name('.payment');
});

/**
 *  * Payment
 */
Route::get('cart/pay/free', [FreeController::class, 'process'])->name('cart.payment.free');

/**
 * * Order Item
 */
// Create an order
Route::resource('order-items', OrderItemController::class)->only(['store']);

// Empty cart
Route::get('/cart/empty', function () {
	return view('ecommerce.cart.empty');
})->middleware(EmptyCart::class)->name('cart.empty');

/**
 * * Order
 */
// Find an order
Route::get('orders/find', [OrderFindController::class, 'index'])->name('orders.find');
Route::post('orders/find', [OrderFindController::class, 'show'])->name('orders.find');

// Order resource routes
Route::resource('orders', OrderController::class)->middleware(OrderRecap::class)->only(['show', 'destroy']);
Route::resource('orders', OrderController::class)->only(['update']);

/**
 * * Refund / Change
 */
Route::prefix('/refund')->name('support.refund.')->group(function () {

	// Search step
	Route::get('/search', [RefundController::class, 'indexSearch'])->middleware(RefundSkip::class)->name('search');
	Route::post('/search', [RefundController::class, 'search'])->name('search');

	// Select order items step
	Route::get('/{changeRequest}/select', [RefundController::class, 'indexSelect'])->middleware(HasChangeRequestAccess::class)->name('select');
	Route::post('/{changeRequest}/select', [RefundController::class, 'select'])->middleware(HasChangeRequestAccess::class)->name('select');

	// Cancel request
	Route::post('/{changeRequest}/cancel', [RefundController::class, 'cancel'])->middleware(HasChangeRequestAccess::class)->name('cancel');
});
