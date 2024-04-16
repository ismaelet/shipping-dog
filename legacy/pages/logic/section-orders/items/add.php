<?php

global $id;

//setup page
$tbl = 'orders';
$index = 'id';
$index_show = 'id';

//form
$searchConfig = [
	'endpoint' => 'designs',
	'value-field' => 'id',
	'show-field' => 'fullName',
	'filter' => 'id,fullName',
];

if ($id) {

	$productID = DBOld::find('product_id FROM orders WHERE id = ?', $id);

	$design = DBOld::row('ID, name FROM designs WHERE id IN (SELECT design_id FROM items WHERE id = ?)', $productID);

	$searchConfig['value-id'] = $design['id'] ?? 0;
	$searchConfig['value-show'] = $design['name'] ?? '';
}

$inputs = [
	[
		'type' => 'search',
		'name' => 'design_id',
		'text' => 'Design',
		'search' => $searchConfig,
	],
	[
		'type' => 'select',
		'name' => 'type_id',
		'text' => 'Tipo',
		'query' => 'ID, IF (gender=2, CONCAT(name," donna"), name) AS name FROM items_types ORDER BY id ASC',
		'field-name' => 'id',
		'field-show' => 'name',
		'parse' => 'int',
	],
	[
		'type' => 'select',
		'name' => 'gender_id',
		'text' => 'Modello',
		'query' => '* FROM items_genders ORDER BY id ASC',
		'field-name' => 'id',
		'field-show' => 'name',
		'parse' => 'int',
		'empty' => true,
	],
	[
		'type' => 'select',
		'name' => 'color_id',
		'text' => 'Colore',
		'query' => '* FROM items_colors ORDER BY id ASC',
		'field-name' => 'id',
		'field-show' => 'name',
		'parse' => 'int',
		'empty' => true,
	],
	[
		'type' => 'select',
		'name' => 'sizeID',
		'text' => 'Taglia',
		'query' => '* FROM items_sizes ORDER BY id ASC',
		'field-name' => 'id',
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

if ($id) $inputs[0]['val'] = $design['id'] ?? 0;

//add
function _expand_add($tbl, $inputs) {

	$cart_id = (int) $_GET['filter'];
	$user_id = DBOld::find('user_id FROM carts WHERE id = ?', $cart_id);

	$design_id = (int) $_POST['design_id'];
	$type_id = (int) $_POST['type_id'];
	$color_id = (int) $_POST['color_id'];
	$gender_id = (int) $_POST['gender_id'];
	$sizeID = (int) $_POST['sizeID'];

	$productID = DBOld::find('id FROM items WHERE design_id = ? AND type_id = ?', [$design_id, $type_id]);

	DBOld::add('orders (cart_id, user_id, product_id, type_id, gender_id, color_id, sizeID) VALUES (?, ?, ?, ?, ?, ?, ?)', [$cart_id, $user_id, $productID, $type_id, $gender_id, $color_id, $sizeID]);

	if (isset($_POST['add-sale'])) {

		$shopID = DBOld::find('user_id FROM designs WHERE id = ?', $design_id);
		$gain = $color_id > 1 ? DBOld::find('gain_c FROM items_prices WHERE product_id = ?', $productID) : DBOld::find('gain_w FROM items_prices WHERE product_id = ?', $productID);

		DBOld::add('sales (design_id, seller_id, amount, gain, type_id, color_id) VALUES (?, ?, ?, ?, ?, ?)', [$design_id, $shopID, 1, $gain, $type_id, $color_id]);

		DBOld::update('stores SET sales=sales+1, total=total+? WHERE user_id = ?', [$gain, $shopID]);
	}
}

//edit
function _expand_edit($tbl, $inputs, $index, $id) {

	$productID = DBOld::find('id FROM items WHERE design_id = ? AND type_id = ?', [$_POST['design_id'], $_POST['type_id']]);

	DBOld::update('orders SET product_id = ?, type_id = ?, gender_id = ?, color_id = ?, sizeID = ? WHERE id = ?', [$productID, $_POST['type_id'], $_POST['gender_id'], $_POST['color_id'], $_POST['sizeID'], $id]);
}
