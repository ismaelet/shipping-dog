<?php

use App\Models\Ecommerce\Cart as EcommerceCart;
use Ecommerce\Cart;

$cartId = $_GET['id'] ?? null;
$email = $_GET['email'] ?? null;


if (logged() && !$email) {
	$cart = EcommerceCart::where('carts.ID', $cartId)
		->where('buyer', userId())
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
	redirect('/order/status');
}

$customerId = $cart->buyer;
$cart = new Cart($cartId);

View::setTitle('Ordine ' . $cart->idCode);

$cart->sendStatusEmail();
