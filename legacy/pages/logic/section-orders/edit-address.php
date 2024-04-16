<?php

global $id;

/*
* Query configuration
*/
$queryConfig['table'] = 'addresses';
$queryConfig['index-field'] = 'id';
$queryConfig['title-field'] = 'full_name';

/**
 * Inputs
 */
$inputs = [
	[
		['type' => 'done', 'done' => 'ok', 'text' => 'Indirizzo modificato correttamente'],
		['type' => 'lbl', 'text' => 'Nominativo'],
		['text' => 'Nominativo di spedizione', 'name' => 'full_name', 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Indirizzo'],
		['text' => 'Via, strada, piazza', 'name' => 'address', 'size' => 3, 'pholder' => 'Indirizzo', 'required' => true],
		['text' => 'Numero', 'name' => 'number', 'size' => 1, 'pholder' => 'Numero civico', 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Città'],
		['text' => 'Città', 'name' => 'city', 'pholder' => 'Città', 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Provincia e CAP'],
		['text' => 'Provincia', 'name' => 'region', 'size' => '2', 'pholder' => 'Provincia', 'required' => true],
		['text' => 'CAP', 'name' => 'zip', 'pholder' => 'CAP', 'size' => '2', 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Numero di telefono'],
		['text' => 'Numero di telefono', 'name' => 'phone', 'pholder' => 'Numero di telefono', 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'E-email di contatto'],
		['text' => 'E-email di contatto', 'name' => 'email', 'pholder' => 'Indirizzo email', 'required' => true],
	]
];
