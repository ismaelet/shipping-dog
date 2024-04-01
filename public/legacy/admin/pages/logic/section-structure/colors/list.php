<?php

/*
* Page configuration
*/
$pageConfig['row-action'] = 'add?id={{id}}';

/*
* Query configuration
*/
$queryConfig['table'] = 'items_colors';
$queryConfig['index'] = 'ID';
$queryConfig['where-search'] = "
	ID = ? OR
	tag LIKE CONCAT('%',?,'%') OR
	name_it LIKE CONCAT('%',?,'%')
";
$queryConfig['order-index'] = 'reorder ASC, ID';
$queryConfig['order-direction'] = 'asc';

/*
* Fields
*/
$fields = [
	['title' => 'ID', 'name' => 'ID'],
	['title' => 'Colore', 'name' => 'color'],
	['title' => 'Nome', 'name' => 'name_it', 'size' => 1],
	['title' => 'Tag', 'name' => 'tag',],
	['title' => 'Ordine', 'name' => 'reorder'],
	['title' => 'Scuro', 'name' => 'dark', 'type' => 'boolean-icon'],
	['title' => 'Spedizione lunga', 'name' => 'preorder', 'type' => 'boolean-icon'],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Modifica', 'url' => 'add?id={{ID}}'],
];

function _expand_results(&$entry, $field, $key, $row) {
	if ($field['name'] == 'color') {
		$entry['value'] = '<span class="color-box" style="--_color: #' . $entry['value'] . ';"></span>#' . $entry['value'];
	}
}
