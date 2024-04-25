<?php

global $id;

//setup page
$tbl = 'carts';

//inputs
$inputs = [
	[
		'type' => 'select',
		'name' => 'shipping_method_id',
		'text' => 'Tipo di spedizione',
		'query' => 'ID, name, tag FROM sys_shipping ORDER BY tier DESC, id ASC',
		'field-name' => 'id',
		'field-show' => 'name',
	],
	[
		'type' => 'textarea',
		'name' => 'address',
		'text' => 'Indirizzo',
		'pholder' => 'Nome e cognome *a capo* via *a capo* civico *a capo* cap *spazio* città *a capo* provincia',
	],
	[
		'type' => 'textarea',
		'name' => 'contacts',
		'text' => 'Contatti',
		'pholder' => 'email *a capo* telefono',
	],
];

//submit
function _expand_add($tbl, $inputs) {

	$shipping_method_id = $_POST['shipping_method_id'];

	$user_id = 'T' . DBOld::add('temp_users (ID) VALUES (NULL)');

	//generate cart
	$cart_id = DBOld::add('carts (user_id, shipping_method_id, status) VALUES (?, ?, 1)', [$user_id, $shipping_method_id]);

	DBOld::update('carts SET time=NOW() WHERE id = ?', [$cart_id]);

	//generate address
	$id = DBOld::add('addresses (user_id, cart_id) VALUES (?, ?)', [$user_id, $cart_id]);

	//Save address
	$addrRaw = $_POST['address'];
	$addrRaw = str_replace(array("(", ")"), array("\r\n", ""), $addrRaw);
	$addrRaw = explode("\r\n", $addrRaw);

	$i = 0;

	//get address name
	while (isset($addrRaw[$i]) && empty(trim($addrRaw[$i]))) $i++;
	if (isset($addrRaw[$i])) $full_name = $addrRaw[$i];
	else $full_name = '';

	$i++;

	//get address
	while (isset($addrRaw[$i]) && empty(trim($addrRaw[$i]))) $i++;
	if (isset($addrRaw[$i])) $addr = $addrRaw[$i];
	else $addr = '';

	$i++;

	//get number
	while (isset($addrRaw[$i]) && empty(trim($addrRaw[$i]))) $i++;
	if (isset($addrRaw[$i])) $num = $addrRaw[$i];
	else $num = '';

	$i++;

	//get zip, city, region
	while (isset($addrRaw[$i]) && empty(trim($addrRaw[$i]))) $i++;

	if (isset($addrRaw[$i])) {

		$line = trim($addrRaw[$i]);

		if (strpos($line, ' ')) {
			$line = explode(' ', $line, 2);
			$zip = $line[0];
			$city = $line[1];
		} else {
			$zip = '';
			$city = '';
		}
	} else {
		$zip = '';
		$city = '';
	}
	$i++;

	//get region
	while (isset($addrRaw[$i]) && empty(trim($addrRaw[$i]))) $i++;
	if (isset($addrRaw[$i])) $reg = $addrRaw[$i];
	else $reg = '';

	$i++;

	$full_name = ucwords(strtolower(trim($full_name)));
	$full_name = str_replace('-', ',', $full_name);
	$addr = ucwords(strtolower(trim($addr)));
	$city = ucwords(strtolower(trim($city)));
	$city = str_replace('-', ',', $city);
	if (empty($reg)) $reg = $city;
	$reg = (strlen(trim($reg)) == 2) ? strtoupper(trim($reg)) : ucwords(strtolower(trim($reg)));

	DBOld::update('addresses SET full_name = ?, address=?, number=?, zip=?, city=?, region=? WHERE id = ?', [$full_name, $addr, $num, $zip, $city, $reg, $id]);

	//get contact data
	$addrRaw = $_POST['contacts'];
	$addrRaw = str_replace("-", "\r\n", $addrRaw);
	$addrRaw = explode("\r\n", $addrRaw);

	$i = 0;

	//get email
	while (isset($addrRaw[$i]) && empty(trim($addrRaw[$i]))) $i++;
	if (isset($addrRaw[$i])) $email = $addrRaw[$i];
	else $email = '';

	$i++;

	//get phone
	while (isset($addrRaw[$i]) && empty(trim($addrRaw[$i]))) $i++;
	if (isset($addrRaw[$i])) $phone = $addrRaw[$i];
	else $phone = '';

	$email = strtolower(trim($email));
	$phone = trim($phone);

	DBOld::update('addresses SET email=?, phone=? WHERE id = ?', [$email, $phone, $id]);
}
