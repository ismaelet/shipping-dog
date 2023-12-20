<?php

global $id;

//setup page
$tbl = 'orders';
$index = 'ID';
$index_show = 'ID';

//form
$searchConfig = [
	'endpoint' => 'designs',
	'value-field' => 'id',
	'show-field' => 'fullName',
	'filter' => 'id,fullName',
];

if ($id) {

	$productID = DB::find('itemID FROM orders WHERE ID = ?', $id);

	$design = DB::row('ID, name FROM designs WHERE ID IN (SELECT desID FROM items WHERE ID = ?)', $productID);

	$searchConfig['value-id'] = $design['ID'] ?? 0;
	$searchConfig['value-show'] = $design['name'] ?? '';
}

$inputs = [
	[
		'type' => 'search',
		'name' => 'desID',
		'text' => 'Design',
		'search' => $searchConfig,
	],
	[
		'type' => 'select',
		'name' => 'typeID',
		'text' => 'Tipo',
		'query' => 'ID, IF (gender=2, CONCAT(name_it," donna"), name_it) AS name FROM items_types ORDER BY ID ASC',
		'field-name' => 'ID',
		'field-show' => 'name',
		'parse' => 'int',
	],
	[
		'type' => 'select',
		'name' => 'gendID',
		'text' => 'Modello',
		'query' => '* FROM items_genders ORDER BY ID ASC',
		'field-name' => 'ID',
		'field-show' => 'name_it',
		'parse' => 'int',
		'empty' => true,
	],
	[
		'type' => 'select',
		'name' => 'colorID',
		'text' => 'Colore',
		'query' => '* FROM items_colors ORDER BY ID ASC',
		'field-name' => 'ID',
		'field-show' => 'name_it',
		'parse' => 'int',
		'empty' => true,
	],
	[
		'type' => 'select',
		'name' => 'sizeID',
		'text' => 'Taglia',
		'query' => '* FROM items_sizes ORDER BY ID ASC',
		'field-name' => 'ID',
		'field-show' => 'name',
		'parse' => 'int',
		'empty' => true,
	],
	[
		'type' => 'cbx',
		'name' => 'add-sale',
		'text' => 'Aggiungi anche la vendita',
	],
];

if ($id) $inputs[0]['val'] = $design['ID'] ?? 0;

//add
function _expand_add($tbl, $inputs) {

	$cartID = (int) $_GET['filter'];
	$buyer = DB::find('buyer FROM carts WHERE ID = ?', $cartID);

	$desID = (int) $_POST['desID'];
	$typeID = (int) $_POST['typeID'];
	$colorID = (int) $_POST['colorID'];
	$gendID = (int) $_POST['gendID'];
	$sizeID = (int) $_POST['sizeID'];

	$productID = DB::find('ID FROM items WHERE desID = ? AND typeID = ?', [$desID, $typeID]);

	DB::add('orders (cartID, buyer, itemID, typeID, gendID, colorID, sizeID) VALUES (?, ?, ?, ?, ?, ?, ?)', [$cartID, $buyer, $productID, $typeID, $gendID, $colorID, $sizeID]);

	if (isset($_POST['add-sale'])) {

		$shopID = DB::find('userID FROM designs WHERE ID = ?', $desID);
		$gain = $colorID > 1 ? DB::find('gain_c FROM items_prices WHERE itemID = ?', $productID) : DB::find('gain_w FROM items_prices WHERE itemID = ?', $productID);

		DB::add('sales (desID, storID, amount, gain, typeID, colorID) VALUES (?, ?, ?, ?, ?, ?)', [$desID, $shopID, 1, $gain, $typeID, $colorID]);

		DB::update('stores SET sales=sales+1, total=total+? WHERE userID = ?', [$gain, $shopID]);
	}
}

//edit
function _expand_edit($tbl, $inputs, $index, $id) {

	$productID = DB::find('ID FROM items WHERE desID = ? AND typeID = ?', [$_POST['desID'], $_POST['typeID']]);

	DB::update('orders SET itemID = ?, typeID = ?, gendID = ?, colorID = ?, sizeID = ? WHERE ID = ?', [$productID, $_POST['typeID'], $_POST['gendID'], $_POST['colorID'], $_POST['sizeID'], $id]);
}
