<?php

//setup page

use App\Models\Ecommerce\Cart;

$error = false;
$title = 'Stato ordine';

//form
$inputs = [
	[
		['text' => 'Indirizzo email', 'name' => 'email', 'required' => true],
	],
	[
		['text' => 'Numero ordine', 'name' => 'id', 'required' => true],
		['type' => 'error', 'text' => 'Nessun ordine trovato corrispondente ai dati inseriti', 'error' => 'no_match'],
	],
];

//submit
if (isset($_POST['submit'])) {

	$id = (int) trim($_POST['id'], '#');
	$email = strtolower(trim($_POST['email']));
	$cart = Cart::join('addresses', 'addresses.cart_id', 'carts.id')
		->where('carts.id', $id)
		->where('email', $email)
		->where('status', '!=', '0')
		->select('carts.*', 'addresses.email')
		->first();

	if ($cart) {
		redirect_old('/order?id=' . $cart->id . '&email=' . $email);
	} else {
		$error = 'no_match';
	}
}
