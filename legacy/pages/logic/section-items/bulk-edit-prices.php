<?php

//setup page
$free = true;

//setup form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Capo'],
		[
			'type' => 'select',
			'name' => 'type_id',
			'text' => 'Tipo',
			'query' => 'ID, IF (gender=2, CONCAT(name," donna"), name) AS name FROM items_types ORDER BY id ASC',
			'field-name' => 'id',
			'field-show' => 'name',
		],
	],
	[
		['type' => 'lbl', 'text' => 'Modifica prezzo base'],
		[
			'name' => 'price-difference',
			'text' => 'Differenza di prezzo',
			'parse' => 'float',
		],
	],
];

//add
if (isset($_POST['submit'])) {

	$type_id = $_POST['type_id'];

	$priceDifference = (float) str_replace(',', '.', $_POST['price-difference']);

	\Legacy\System\Classes\LegacyDB::update('prices_default SET price=price+? WHERE type_id=?', [$priceDifference, $type_id]);
	\Legacy\System\Classes\LegacyDB::update('prices_stores SET price=price+? WHERE type_id=?', [$priceDifference, $type_id]);

	redirect_old();
}
