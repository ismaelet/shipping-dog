<?php

global $id;

//setup page
$tbl = 'addresses';
$index = 'cart_id';
$title = 'full_name';

//get
$id = $_GET['id'] ?? false;

//create entry if doesn't exists
if ($id && !\Legacy\System\Classes\LegacyDB::exists('addresses WHERE cart_id = ?', [$id])) {

	$user_id = \Legacy\System\Classes\LegacyDB::find('user_id FROM carts WHERE id = ?', [$id]);

	\Legacy\System\Classes\LegacyDB::add('addresses (cart_id, user_id) VALUES (?, ?)', [$id, $user_id]);
}

//submit
if (isset($_POST['edit'])) {

	$full_name = ucwords(strtolower(trim($_POST['full_name'])));
	$addr = ucwords(strtolower(trim($_POST['address'])));
	$num = trim($_POST['number']);
	$zip = strtoupper(trim($_POST['zip']));
	$city = ucwords(strtolower(trim($_POST['city'])));
	$reg = strtoupper(trim($_POST['region']));
	$email = strtolower(trim($_POST['email']));
	$phone = trim($_POST['phone']);

	$comment = $_POST['comment'];

	\Legacy\System\Classes\LegacyDB::update('addresses SET full_name = ?, address=?, number=?, zip=?, city=?, region=?, email=?, phone=? WHERE cart_id = ?', [$full_name, $addr, $num, $zip, $city, $reg, $email, $phone, $id]);

	redirect_old('list' . $hash);
}

//form
$comment = '';

$inputs = [
	[
		'pholder' => 'Nominativo di spedizione',
		'name' => 'full_name',
		'max' => 50,
	],
	[
		'pholder' => 'Indirizzo',
		'name' => 'address',
		'max' => 50,
		'size' => 'med',
	],
	[
		'pholder' => 'Numero civico',
		'name' => 'number',
		'max' => 10,
		'size' => 'xshr',
	],
	[
		'pholder' => 'Città',
		'name' => 'city',
		'max' => 30,
	],
	[
		'pholder' => 'Codice postale',
		'name' => 'zip',
		'max' => 10,
		'size' => 'shr',
	],
	[
		'pholder' => 'Provincia',
		'name' => 'region',
		'max' => 2,
		'size' => 'shr',
	],
	[
		'pholder' => 'email',
		'name' => 'email',
		'max' => 50,
	],
	[
		'pholder' => 'Numero di telefono',
		'name' => 'phone',
		'max' => 30,
	],
	[
		'type' => 'textarea',
		'pholder' => 'Commento',
		'name' => 'comment',
		'val' => $comment,
	],
];
