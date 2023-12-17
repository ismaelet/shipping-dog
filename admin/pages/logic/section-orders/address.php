<?php

global $id;

//setup page
$tbl = 'addresses';
$index = 'cartID';
$title = 'aname';

//get
$id = $_GET['id'] ?? false;

//create entry if doesn't exists
if ($id && !DB::exists('addresses WHERE cartID=?', [$id])) {

	$buyer = DB::find('buyer FROM carts WHERE ID=?', [$id]);

	DB::add('addresses (cartID, buyer) VALUES (?, ?)', [$id, $buyer]);
}

//submit
if (isset($_POST['edit'])) {

	$aname = ucwords(strtolower(trim($_POST['aname'])));
	$addr = ucwords(strtolower(trim($_POST['address'])));
	$num = trim($_POST['number']);
	$zip = strtoupper(trim($_POST['zip']));
	$city = ucwords(strtolower(trim($_POST['city'])));
	$reg = strtoupper(trim($_POST['region']));
	$email = strtolower(trim($_POST['email']));
	$phone = trim($_POST['phone']);

	$comment = $_POST['comment'];

	DB::update('addresses SET aname=?, address=?, number=?, zip=?, city=?, region=?, email=?, phone=? WHERE cartID=?', [$aname, $addr, $num, $zip, $city, $reg, $email, $phone, $id]);

	DB::update('carts_data SET comm_vip=? WHERE cartID=?', [$comment, $id]);

	redirect('list' . $hash);
}

//form
$comment = DB::find('comm_vip FROM carts_data WHERE cartID=?', [$id]) ?? '';

$inputs = [
	[
		'pholder' => 'Nominativo di spedizione',
		'name' => 'aname',
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
