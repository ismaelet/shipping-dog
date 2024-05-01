<?php

use App\Models\Ecommerce\Cart;

$_SESSION['payment_type'] = 'buy';
$_SESSION['payment_cart_id'] = $cart_id = cart()?->id;

$cart = Cart::find($cart_id);

if ($cart) {
	require_once(base_path() . '/legacy/listeners/shared.php');

	redirect_old($cart->recapUrl());
} else {
	redirect_old('/cart');
}
