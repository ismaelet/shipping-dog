<?php

//setup page
$free = true;

//setup form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Capo'],
		[
			'type' => 'select',
			'name' => 'typeId',
			'text' => 'Tipo',
			'query' => 'ID, IF (gender=2, CONCAT(name_it," donna"), name_it) AS name FROM items_types ORDER BY ID ASC',
			'field-name' => 'ID',
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

	$typeId = $_POST['typeId'];

	$priceDifference = (float) str_replace(',', '.', $_POST['price-difference']);

	DBOld::update('prices_default SET price=price+? WHERE typeId=?', [$priceDifference, $typeId]);
	DBOld::update('prices_stores SET price=price+? WHERE typeId=?', [$priceDifference, $typeId]);

	redirect_old();
}
