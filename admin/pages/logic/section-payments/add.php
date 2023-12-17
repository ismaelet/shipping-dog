<?php

/*
* Page configuration
*/
// $pageConfig['confirm'] = $pageConfig['confirm'] ?? null;

/*
* Query configuration
*/
$queryConfig['table'] = 'stores_payments';
$queryConfig['index-field'] = 'ID';
$queryConfig['title-field'] = 'ID';

/*
* Form fields
*/
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Negozio',],
		[
			'type' => 'search',
			'text' => 'Negozio',
			'name' => 'storeID',
			'search' => 'sellers',
			'required' => true,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Valore',],
		['name' => 'value', 'text' => 'Valore', 'required' => true, 'parse' => 'float']
	],
	[
		'type' => 'cbx',
		'name' => 'other',
		'val' => 1,
		'text' => 'Tipo di transferenza',
		'text' => 'Non è un pagamento ordinario. Il valore verrà aggiunto o sottratto da un campo separato. Se si vuole togliere l\'importo dal totale mettere un valore negativo.'
	],
];

/*
* Form handling
*/
function _expand_add($tbl, $inputs) {

	$value = str_replace(',', '.', $_POST['value']);

	if (isset($_POST['other'])) DB::update('stores SET flat=flat+? WHERE userID=?', [$value, $_POST['storeID']]);
	else DB::update('stores SET payed=payed+?, request=0 WHERE userID=?', [$value, $_POST['storeID']]);

	form_db_add($tbl, $inputs);
}
