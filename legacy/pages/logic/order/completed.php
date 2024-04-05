<?php

use App\Models\Ecommerce\Cart as EcommerceCart;
use Ecommerce\Cart;

$cartId = $_GET['id'] ?? null;
$email = $_GET['email'] ?? null;


if (auth()->check() && !$email) {
	$cart = EcommerceCart::where('carts.ID', $cartId)
		->where('buyer', auth()->id())
		->where('status', '!=', '0')
		->first();
} else {
	$cart = EcommerceCart::join('addresses', 'addresses.cartID', 'carts.ID')
		->where('carts.ID', $cartId)
		->where('email', $email)
		->where('status', '!=', '0')
		->select('carts.*')
		->first();
}

if (!$cart) {
	redirect_old('/order/status');
}

$customerId = $cart->buyer;
$cart = new Cart($cartId);

ViewOld::setTitle('Ordine ' . $cart->idCode);

$cart->sendStatusEmail();
