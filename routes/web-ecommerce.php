<?php

use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Ecommerce\OrderController;
use App\Http\Controllers\LegacyController;
use App\Http\Middleware\Cart;
use App\Http\Middleware\EmptyCart;
use Illuminate\Support\Facades\Route;

/**
 * * Shop
 */
Route::any('/shop', LegacyController::class)->name('shop');

/**
 * * Order
 */
// Create an order
Route::resource('/orders', OrderController::class)->only(['store']);

/**
 * * Cart
 */
// General cart routes
Route::get('/cart', function () {
	$cartStep = cart()?->stepTag();

	if (!$cartStep) {
		return redirect()->route('cart.empty');
	}

	if (in_array($cartStep, ['access'])) {
		return redirect()->route('cart.' . $cartStep);
	}

	// ! Legacy support
	return redirect('/cart/' . $cartStep);
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
