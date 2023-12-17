<?php

use Ecommerce\Product;

global $id;

//setup page
$tbl = 'items_types';
$index = 'ID';
$index_show = 'name';

//form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Tag'],
		['name' => 'tag', 'text' => 'Tag', 'required' => true],
	],
	[
		['type' => 'lbl', 'text' => 'Nome catalogo'],
		['name' => 'name', 'text' => 'Nome catalogo'],
	],
	[
		['type' => 'lbl', 'text' => 'Nome cliente'],
		['name' => 'name_it', 'text' => 'Nome cliente'],
	],
	[
		['type' => 'lbl', 'text' => 'Nome plurale'],
		['name' => 'names_it', 'text' => 'Nome plurale'],
	],
	[
		['type' => 'lbl', 'text' => 'Modello'],
		[
			'type' => 'select',
			'name' => 'gender',
			'text' => 'Modello',
			'query' => 'ID, name_it FROM items_genders',
			'field-name' => 'ID',
			'field-show' => 'name_it',
			'empty' => true,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Modello alternativo'],
		[
			'type' => 'select',
			'name' => 'sibling',
			'text' => 'Modello',
			'query' => 'ID, name FROM items_types ORDER BY ID ASC',
			'field-name' => 'ID',
			'field-show' => 'name',
			'empty' => true,
		],
	],
	[
		['type' => 'cbx', 'name' => 'unisex', 'text' => 'È unisex', 'value' => 1],
	],
];

//sizes
$array = [['type' => 'lbl', 'text' => 'Taglie']];
$typeSizes = explode(',', DB::find('sizes FROM items_types WHERE ID=?', [$id]));

$res = DB::select('tag, name FROM items_sizes ORDER BY ID ASC');

while ($row = $res->fetch_assoc()) {

	$size = ['type' => 'cbx', 'name' => 'sizes-cbx[]', 'value' => $row['tag'], 'ignore' => true, 'text' => $row['name'], 'size' => 1];

	if (in_array($row['tag'], $typeSizes)) $size['checked'] = true;

	$array[] = $size;
}

$inputs[] = $array;

//positions
$array = [['type' => 'lbl', 'text' => 'Posizioni di stampa']];
$typePositions = explode(',', DB::find('positions FROM items_types WHERE ID=?', [$id]));

$res = DB::select('ID, name_it FROM items_positions ORDER BY ID ASC');

while ($row = $res->fetch_assoc()) {

	$size = ['type' => 'cbx', 'name' => 'positions-cbx[]', 'value' => $row['ID'], 'ignore' => true, 'text' => $row['name_it'], 'size' => 1];

	if (in_array($row['ID'], $typePositions)) $size['checked'] = true;

	$array[] = $size;
}

$inputs[] = $array;

//add
function _expand_add($tbl, $inputs) {

	refine_inputs($inputs);

	//add type
	$newId = form_db_add($tbl, $inputs);

	//add prices
	DB::add('prices_default (typeID) VALUES (?)', [$newId]);

	//add measures
	if (isset($_POST['sizes-cbx']))
		foreach ($_POST['sizes-cbx'] as $size)
			DB::add('items_types_measures (typeId, size) VALUES (?, ?)', [$newId, $size]);

	//add mockups
	if (isset($_POST['positions-cbx']))
		foreach ($_POST['positions-cbx'] as $position)
			DB::add('items_mockups (typeID, positionID) VALUES (?, ?)', [$newId, $position]);

	//create items
	$designs = DB::select('ID FROM designs WHERE status=1');

	while ($design = $designs->fetch_assoc()) {
		Product::new($design['ID'], $newId);
	}
}

//edit
function _expand_edit($tbl, $inputs, $index, $id) {
	refine_inputs($inputs);
	form_db_update($tbl, $inputs, $index, $id);
}

function refine_inputs(&$inputs) {
	$sizes = isset($_POST['sizes-cbx']) ? implode(',', $_POST['sizes-cbx']) : false;
	$positions = isset($_POST['positions-cbx']) ? implode(',', $_POST['positions-cbx']) : false;

	if ($sizes) $inputs[] = ['name' => 'sizes', 'value' => $sizes];
	if ($positions) $inputs[] = ['name' => 'positions', 'value' => $positions];
}
