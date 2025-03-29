<?php

//setup page

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;

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
	$order = Order::join('addresses', 'addresses.order_id', 'orders.id')
		->where('orders.id', $id)
		->where('email', $email)
		->where('status', '!=', EcommerceHelper::statusFromTag('open'))
		->select('orders.*', 'addresses.email')
		->first();

	if ($order) {
		redirect_old($order->recapUrlWithToken());
	} else {
		$error = 'no_match';
	}
}
