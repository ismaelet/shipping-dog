<?php

/**
 * Query configuration
 */

use App\Http\Controllers\Admin\FormController;

$queryConfig['table'] = 'prices_default';
$queryConfig['index-field'] = 'type_id';
$queryConfig['title-field'] = 'type_id';

//form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Prezzo'],
		['name' => 'price', 'text' => 'Prezzo'],
	],
	[
		['type' => 'lbl', 'text' => 'Guadagno da capi bianchi'],
		['name' => 'gain_w', 'text' => 'Guadagno da capi bianchi'],
	],
	[
		['type' => 'lbl', 'text' => 'Guadagni da capi colorati'],
		['name' => 'gain_c', 'text' => 'Guadagni da capi colorati'],
	],
];

// FormController::setEdit(function ($id) {
// });
