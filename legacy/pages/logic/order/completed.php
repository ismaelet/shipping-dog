<?php

use App\Models\Ecommerce\Cart;

$cart_id = $_GET['id'] ?? null;
$email = $_GET['email'] ?? null;

if (auth()->check() && !$email) {
	$cart = Cart::where('carts.id', $cart_id)
		->where('user_id', auth()->id())
		->where('status', '!=', '0')
		->first();
} else {
	$cart = Cart::join('addresses', 'addresses.cart_id', 'carts.id')
		->where('carts.id', $cart_id)
		->where('email', $email)
		->where('status', '!=', '0')
		->select('carts.*')
		->first();
}

if (!$cart) {
	redirect_old('/order/status');
}

$customerId = $cart->user_id;

\Legacy\System\Classes\LegacyView::setTitle('Ordine ' . $cart->idCode());
