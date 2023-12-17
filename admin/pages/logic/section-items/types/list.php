<?php

//generate again images
if (isset($_POST['delete'])) {

	$id = $_POST['ID'];

	delete_type($id, true);
}

//select table
$tbl = 'items_types';
$index = 'ID';
$query['order'] = 'ASC';

//handle search
$query['filter'] = "
	ID=? OR
	tag LIKE CONCAT('%',?,'%') OR
	name LIKE CONCAT('%',?,'%') OR
	name_it LIKE CONCAT('%',?,'%') OR
	names_it LIKE CONCAT('%',?,'%')
";

//select fields
$fields = [
	['title' => 'ID', 'name' => 'ID', 'size' => 's'],
	['title' => 'Nome', 'name' => 'name', 'size' => 'm'],
	['title' => 'Modello', 'name' => 'gender', 'type' => 'find', 'find' => 'name_it FROM items_genders WHERE ID=?', 'size' => 'm'],
	['title' => 'Colori', 'name' => 'colors', 'no-overflow' => true, 'size' => 'l'],
	['title' => 'Categoria', 'name' => 'category', 'size' => 's'],
	['title' => 'Pubblico', 'name' => 'hidden', 'switch' => ['NO', 'SI'], 'size' => 's'],

	['type' => 'hidden', 'name' => 'ID'],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'url', 'title' => 'Prezzi', 'color' => 'yel', 'url' => 'prices?id={{ID}}'],
	['type' => 'url', 'title' => 'Dimensioni', 'color' => 'gre', 'url' => 'measures?id={{ID}}'],
	['type' => 'delete'],
];

function _expand_results(&$entry, $field, $key, $row) {

	if ($field['name'] == 'colors') {

		$colors = explode(',', $entry['value']);

		$entry['value'] = [];

		foreach ($colors as $color)
			if ($colorString = DB::find('name_it FROM items_colors WHERE tag=?', [$color])) $entry['value'][] = $colorString;

		asort($entry['value']);

		$entry['value'] = !empty($entry['value']) ? implode(', ', $entry['value']) : '-';
	} else if ($field['name'] == 'category')
		$entry['value'] = $entry['value'] == 'c' ? 'Abbigliamento' : 'Accessori';
}
