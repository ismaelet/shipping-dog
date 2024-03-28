<?php

/**
 * Query configuration
 */
$queryConfig['table'] = 'items_colors';
$queryConfig['index-field'] = 'ID';
$queryConfig['title-field'] = 'name_it';

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
		['name' => 'name_it', 'text' => 'Nome'],
	],
	[
		['type' => 'lbl', 'text' => 'Colore HEX'],
		['name' => 'color', 'text' => 'Colore HEX'],
	],
	[
		['type' => 'lbl', 'text' => 'Ordine'],
		['name' => 'reorder', 'text' => 'Ordine', 'parse' => 'int'],
	],
];
