<?php

/**
 * Query configuration
 */
$queryConfig['table'] = 'items_colors';
$queryConfig['index-field'] = 'id';
$queryConfig['title-field'] = 'name';

/**
 * Form inputs
 */
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Tag'],
		['name' => 'tag', 'text' => 'Tag', 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Nome'],
		['name' => 'name', 'text' => 'Nome'],
	],
	[
		['type' => 'lbl', 'text' => 'Colore HEX'],
		['name' => 'color', 'text' => 'Colore HEX'],
	],
	[
		['type' => 'lbl', 'text' => 'Ordine'],
		['name' => 'reorder', 'text' => 'Ordine', 'parse' => 'int'],
	],
	[
		['name' => 'dark', 'text' => 'Il colore è scuro', 'type' => 'cbx',],
	],
];
