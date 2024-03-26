<?php

/**
 * Query configuration
 */
$queryConfig['table'] = 'items_sizes';
$queryConfig['index-field'] = 'ID';
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
		['type' => 'lbl', 'text' => 'Sovrapprezzo'],
		['name' => 'price', 'text' => 'Sovrapprezzo'],
	],
];
