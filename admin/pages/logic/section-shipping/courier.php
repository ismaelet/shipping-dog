<?php

use Ecommerce\Cart;
use Shuchkin\SimpleXLSXGen as XLS;

/*
* Page Configuration
*/

$pageConfig['free'] = true;

$cartCount = DB::count('carts WHERE status=1 && (shipID=2 OR shipID=1) ORDER BY time ASC');

// submit
if (isset($_POST['submit'])) {

	$rows = [];

	$cartRows = DB::select('ID FROM carts WHERE status = 1 && (shipID = 2 OR shipID = 1) ORDER BY time ASC');

	while ($cartRow = $cartRows->fetch_assoc()) {
		$cart = new Cart($cartRow['ID']);
		$address = $cart->address;

		$rows[] = [
			$address->fullname,
			$address->address . ', ' . $address->number,
			$address->city,
			$address->zip,
			$address->region,
			$address->email,
			'Tel: ' . $address->phone,
			1,
			1,
			$address->exchange ? 24 : ''
		];

		DB::update('carts SET status = 2 WHERE ID = ?', [$cartRow['ID']]);
	}

	$xlsx = XLS::fromArray($rows);
	$xlsx->saveAs(SERVER_ROOT . '/uploads/documents/Etichette-Corriere.xlsx');
	$xlsx->downloadAs('Etichette-Corriere.xlsx');
}
