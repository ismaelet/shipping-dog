<?php

/*
* Page configuration
*/
// $pageConfig['confirm'] = $pageConfig['confirm'] ?? null;

/*
* Query configuration
*/
$queryConfig['table'] = 'stores_payments';
$queryConfig['index-field'] = 'id';
$queryConfig['title-field'] = 'id';

/*
* Form fields
*/
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Negozio',],
		[
			'type' => 'number',
			'text' => 'ID Negozio',
			'name' => 'seller_id',
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

	if (isset($_POST['other'])) \Legacy\System\Classes\LegacyDB::update('stores SET flat=flat+? WHERE user_id = ?', [$value, $_POST['seller_id']]);
	else \Legacy\System\Classes\LegacyDB::update('stores SET payed=payed+?, request=0 WHERE user_id = ?', [$value, $_POST['seller_id']]);

	form_db_add($tbl, $inputs);

	redirect_old('/admin/payments/index');
}
