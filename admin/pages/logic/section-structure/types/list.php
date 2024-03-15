<?php

/*
* Page configuration
*/
$pageConfig['row-action'] = 'add?id={{id}}';

/*
* Query configuration
*/
$queryConfig['table'] = 'items_types';
$queryConfig['index'] = 'ID';
$query['where-search'] = "
	ID = ? OR
	tag LIKE CONCAT('%',?,'%') OR
	name LIKE CONCAT('%',?,'%') OR
	name_it LIKE CONCAT('%',?,'%') OR
	names_it LIKE CONCAT('%',?,'%')
";

/*
* Fields
*/
$fields = [
	['title' => 'ID', 'name' => 'ID'],
	['title' => 'Nome', 'name' => 'name', 'size' => 2],
	['title' => 'Modello', 'name' => 'gender', 'type' => 'find', 'find' => 'name_it FROM items_genders WHERE ID = ?', 'size' => 1],
	['title' => 'Colori', 'name' => 'colors', 'no-overflow' => true, 'size' => 3],
	['title' => 'Categoria', 'name' => 'category',],
	['title' => 'Pubblico', 'name' => 'hidden', 'parse' => 'flip', 'type' => 'boolean-icon'],

	['type' => 'hidden', 'name' => 'ID'],

	['type' => 'btn'],
];

$buttons = [
	['title' => 'Mockup', 'color' => 'green', 'url' => 'mockups?id={{ID}}'],
];

function _expand_results(&$entry, $field, $key, $row) {
	if ($field['name'] == 'colors') {

		$colors = explode(',', $entry['value']);

		$entry['value'] = [];

		foreach ($colors as $color)
			if ($colorString = DB::find('name_it FROM items_colors WHERE tag = ?', [$color])) $entry['value'][] = $colorString;

		asort($entry['value']);

		$entry['value'] = !empty($entry['value']) ? implode(', ', $entry['value']) : '-';
	} else if ($field['name'] == 'category')
		$entry['value'] = $entry['value'] == 'c' ? 'Abbigliamento' : 'Accessori';
}

if (isset($_POST['delete'])) {
	$id = $_POST['ID'];
}
