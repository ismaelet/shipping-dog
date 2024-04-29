<?php

global $id;

/*
* Page configuration
*/
// $pageConfig['confirm'] = $pageConfig['confirm'] ?? null;

/*
* Query configuration
*/
$queryConfig['table'] = 'codes';
$queryConfig['index-field'] = 'id';
$queryConfig['title-field'] = 'code';

$inputs = [
	[
		['type' => 'lbl', 'text' => 'Codice',],
		[
			'name' => 'code',
			'text' => 'Codice',
			'max' => 11,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Validità.<br><span class="txt-reg">Il valore di default è 1 anno (occhio alla data finale!).<br>La data finale non è inclusa nel periodo dello sconto</span>',],
		[
			'type' => 'date',
			'name' => 'time_start',
			'size' => 2,
			'value' => date('Y-m-d'),
		],
		[
			'type' => 'date',
			'name' => 'time_end',
			'size' => 2,
			'value' => date('Y-m-d', strtotime('+1 year')),
		],
	],
	[
		['type' => 'lbl', 'text' => 'Capo e colore (opzionali)',],
		[
			'type' => 'select',
			'name' => 'type_id',
			'text' => 'Tipo',
			'query' => 'id, IF (gender = 2, CONCAT(name," donna"), name) AS name FROM items_types ORDER BY id ASC',
			'field-name' => 'id',
			'field-show' => 'name',
			'parse' => 'int',
			'empty' => true,
		],
		[
			'type' => 'select',
			'name' => 'color_id',
			'text' => 'Colore',
			'query' => 'id, name FROM items_colors ORDER BY id ASC',
			'field-name' => 'id',
			'field-show' => 'name',
			'parse' => 'int',
			'empty' => true,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Altri filtri (opzionali)',],
		[
			'type' => 'number',
			'text' => 'Applica solo a questi design',
			'name' => 'design_id',
			'value' => $id ? \Legacy\System\Classes\LegacyDB::find('design_id FROM codes WHERE id = ?', [$id]) : null,
			'value-show' => $id ? \Legacy\System\Classes\LegacyDB::find('design_id FROM codes WHERE id = ?', [$id]) : null,
			'parse' => 'int',
		],
		[
			'type' => 'number',
			'text' => 'Applica solo a questo shop',
			'name' => 'seller_id',
			'value' => $id ? \Legacy\System\Classes\LegacyDB::find('seller_id FROM codes WHERE id = ?', [$id]) : null,
			'value-show' => $id ? \Legacy\System\Classes\LegacyDB::find('seller_id FROM codes WHERE id = ?', [$id]) : null,
			'parse' => 'int',
		],
	]
];

array_push(
	$inputs,
	[
		['type' => 'lbl', 'text' => 'Entità dello sconto.<br><span class="txt-reg">Se si mette una percentuale lasciare vuoto l\'importo fisso in € e viceversa</span>',],
		[
			'name' => 'mod_us',
			'text' => '- % a carico nostro',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'mod_them',
			'text' => '- % a carico dello store',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'flat_us',
			'text' => '- € a carico nostro',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'flat_them',
			'text' => '- € a carico dello store',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'mod_us2',
			'text' => '- % nostro per 2 prodotti',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'mod_them2',
			'text' => '- % negozio per 2 prodotti',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'flat_us2',
			'text' => '- € nostro per 2 prodotti',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'flat_them2',
			'text' => '- € negozio per 2 prodotti',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'mod_us3',
			'text' => '- % nostro per 3+ prodotti',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'mod_them3',
			'text' => '- % negozio per 3+ prodotti',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'flat_us3',
			'text' => '- € nostro per 3+ prodotti',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
		[
			'name' => 'flat_them3',
			'text' => '- € negozio per 3+ prodotti',
			'size' => 1,
			'maxlenght' => 3,
			'parse' => 'int',
		],
	],
	[
		[
			'type' => 'cbx',
			'name' => 'only_one',
			'text' => '<strong>Il codice è applicato solo al primo articolo</strong>',
		],
		[
			'type' => 'cbx',
			'name' => 'permanent',
			'text' => '<strong>Il codice è riusabile</strong> (il codice rimane attivo anche dopo essere stato usato, fin quando non viene cancellato a mano)',
		],
		[
			'type' => 'cbx',
			'name' => 'zero',
			'text' => "<strong>Lo sconto è uguale al guadagno dell'arista</strong> (l'entità dello sconto diventa uguale al guadagno dell'artista, quindi non serve specificare percentuali o importi fissi, se si vuole che l'artista guadagni 0 selezionare anche il box sotto)",
		],
		[
			'type' => 'cbx',
			'name' => 'nogain',
			'text' => "<strong>Il codice azzera il guadagno dell'artista</strong> (l'artista guadagna 0, non ha influenza sull'entità dello sconto)",
		],
		[
			'type' => 'cbx',
			'name' => 'one_per_acccount',
			'text' => '<strong>Il codice può essere usato solo una volta per utente</strong> (inutile se il codice non è riusabile)',
		],
		[
			'type' => 'cbx',
			'name' => 'shipping',
			'text' => '<strong>Il codice azzera le spese di spedizione</strong>',
		],
	]
);

// setup code
if (!$id) {

	$new = false;
	while (!$new) {

		$code = sprintf('%06d', rand(100, 999999));

		if (!\Legacy\System\Classes\LegacyDB::exists('codes WHERE code=?', [$code])) $new = true;
	}

	$inputs[0][1]['value'] = $code;
}

// add
function _expand_add($tbl, $inputs) {
	$_POST['code'] = strtoupper($_POST['code']);
	$_POST['code'] = str_replace(' ', '', $_POST['code']);

	form_db_add($tbl, $inputs);
}

// edit
function _expand_edit($tbl, $inputs, $index, $id) {
	$_POST['code'] = strtoupper($_POST['code']);
	$_POST['code'] = str_replace(' ', '', $_POST['code']);

	form_db_update($tbl, $inputs, $index, $id);
}
