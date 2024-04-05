<?php

//setup page

use Ecommerce\Cart;

$id = $_GET['id'] ?? false;
$cart = new Cart($id);
$security = $_GET['key'] ?? false;
$done = $_SESSION['form-done'] ?? false;
unset($_SESSION['form-done']);

//fetch data
if (!$address = DBOld::row('* FROM addresses WHERE cartID = ? AND security=?', [$id, $security])) redirect_old('/');

$status = DBOld::find('status FROM carts WHERE ID = ?', [$id]);

if (!$status) redirect_old('/cart');

//form
$inputs = [
	[
		['type' => 'done', 'done' => 'ok', 'text' => 'Indirizzo modificato correttamente'],
		['type' => 'lbl', 'text' => 'Nominativo'],
		['text' => 'Nominativo di spedizione', 'name' => 'aname', 'value' => $address['aname'], 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Indirizzo'],
		['text' => 'Via, strada, piazza', 'name' => 'address', 'size' => '3', 'pholder' => 'Indirizzo', 'value' => $address['address'], 'required' => true],
		['text' => 'Numero', 'name' => 'number', 'size' => '1', 'pholder' => 'Numero civico', 'value' => $address['number'], 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Città'],
		['text' => 'Città', 'name' => 'city', 'pholder' => 'Città', 'value' => $address['city'], 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Provincia e CAP'],
		['text' => 'Provincia', 'name' => 'region', 'size' => '2', 'pholder' => 'Provincia', 'value' => $address['region'], 'required' => true],
		['text' => 'CAP', 'name' => 'zip', 'pholder' => 'CAP', 'size' => '2', 'value' => $address['zip'], 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Numero di telefono'],
		['text' => 'Numero di telefono', 'name' => 'phone', 'pholder' => 'Numero di telefono', 'value' => $address['phone'], 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'E-email di contatto'],
		['text' => 'E-email di contatto', 'name' => 'email', 'pholder' => 'Indirizzo email', 'value' => $address['email'], 'required' => true],
	]
];

//submit
if (isset($_POST['submit'])) {

	form_db_update('addresses', $inputs, 'cartID', $id);

	$_SESSION['form-done'] = true;

	redirect_old();
}
