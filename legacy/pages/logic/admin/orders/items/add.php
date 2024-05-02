<?php

global $id;

//setup page
$tbl = 'order_items';
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

	$productID = \Legacy\System\Classes\LegacyDB::find('product_id FROM order_items WHERE id = ?', $id);

	$design = \Legacy\System\Classes\LegacyDB::row('ID, name FROM designs WHERE id IN (SELECT design_id FROM items WHERE id = ?)', $productID);

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
		'query' => 'ID, IF (gender=2, CONCAT(name," donna"), name) AS name FROM product_types ORDER BY id ASC',
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
		'name' => 'size_id',
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

	$order_id = (int) $_GET['filter'];
	$user_id = \Legacy\System\Classes\LegacyDB::find('user_id FROM orders WHERE id = ?', $order_id);

	$design_id = (int) $_POST['design_id'];
	$type_id = (int) $_POST['type_id'];
	$color_id = (int) $_POST['color_id'];
	$gender_id = (int) $_POST['gender_id'];
	$size_id = (int) $_POST['size_id'];

	$productID = \Legacy\System\Classes\LegacyDB::find('id FROM items WHERE design_id = ? AND type_id = ?', [$design_id, $type_id]);

	\Legacy\System\Classes\LegacyDB::add('order_items (order_id, user_id, product_id, type_id, gender_id, color_id, size_id) VALUES (?, ?, ?, ?, ?, ?, ?)', [$order_id, $user_id, $productID, $type_id, $gender_id, $color_id, $size_id]);

	if (isset($_POST['add-sale'])) {

		$shopID = \Legacy\System\Classes\LegacyDB::find('user_id FROM designs WHERE id = ?', $design_id);
		$gain = $color_id > 1 ? \Legacy\System\Classes\LegacyDB::find('gain_c FROM items_prices WHERE product_id = ?', $productID) : \Legacy\System\Classes\LegacyDB::find('gain_w FROM items_prices WHERE product_id = ?', $productID);

		\Legacy\System\Classes\LegacyDB::add('sales (design_id, seller_id, amount, gain, type_id, color_id) VALUES (?, ?, ?, ?, ?, ?)', [$design_id, $shopID, 1, $gain, $type_id, $color_id]);

		\Legacy\System\Classes\LegacyDB::update('stores SET sales=sales+1, total=total+? WHERE user_id = ?', [$gain, $shopID]);
	}
}

//edit
function _expand_edit($tbl, $inputs, $index, $id) {

	$productID = \Legacy\System\Classes\LegacyDB::find('id FROM items WHERE design_id = ? AND type_id = ?', [$_POST['design_id'], $_POST['type_id']]);

	\Legacy\System\Classes\LegacyDB::update('order_items SET product_id = ?, type_id = ?, gender_id = ?, color_id = ?, size_id = ? WHERE id = ?', [$productID, $_POST['type_id'], $_POST['gender_id'], $_POST['color_id'], $_POST['size_id'], $id]);
}
