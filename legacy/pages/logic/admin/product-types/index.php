<?php

/*
* Page configuration
*/
$pageConfig['row-action'] = 'add?id={{id}}';

/*
* Query configuration
*/
$queryConfig['table'] = 'items_types';
$queryConfig['index'] = 'id';
$query['where-search'] = "
	id = ? OR
	tag LIKE CONCAT('%',?,'%') OR
	name LIKE CONCAT('%',?,'%') OR
	name LIKE CONCAT('%',?,'%') OR
	names LIKE CONCAT('%',?,'%')
";

/*
* Fields
*/
$fields = [
	['title' => 'id', 'name' => 'id'],
	['title' => 'Nome', 'name' => 'name', 'size' => 2],
	['title' => 'Modello', 'name' => 'gender', 'type' => 'find', 'find' => 'name FROM items_genders WHERE id = ?', 'size' => 1],
	['title' => 'Colori', 'name' => 'colors', 'no-overflow' => true, 'size' => 3],
	['title' => 'Categoria', 'name' => 'category',],
	['title' => 'Pubblico', 'name' => 'hidden', 'parse' => 'flip', 'type' => 'boolean-icon'],

	['type' => 'hidden', 'name' => 'id'],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['title' => 'Mockup', 'color' => 'green', 'url' => 'mockups/edit?id={{id}}'],
	['title' => 'Prezzi', 'color' => 'yellow', 'url' => 'prices?id={{id}}'],
];

function _expand_results(&$entry, $field, $key, $row) {
	if ($field['name'] == 'colors') {

		$colors = explode(',', $entry['value']);

		$entry['value'] = [];

		foreach ($colors as $color)
			if ($colorString = DBOld::find('name FROM items_colors WHERE tag = ?', [$color])) $entry['value'][] = $colorString;

		asort($entry['value']);

		$entry['value'] = !empty($entry['value']) ? implode(', ', $entry['value']) : '-';
	} else if ($field['name'] == 'category')
		$entry['value'] = $entry['value'] == 'c' ? 'Abbigliamento' : 'Accessori';
}

if (isset($_POST['delete'])) {
	$id = $_POST['id'];
}
