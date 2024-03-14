<?php

use App\Http\Controllers\Admin\FormController;
use App\Models\Ecommerce\BasePrice;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product\ProductTypeMeasure;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Product\ProductTypeMockup;
use Ecommerce\Product;

global $id;

/**
 * Query configuration
 */
$queryConfig['table'] = 'items_types';
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
		['type' => 'lbl', 'text' => 'Nome per gli amministratori'],
		['name' => 'name', 'text' => 'Nome per gli amministratori'],
	],
	[
		['type' => 'lbl', 'text' => 'Nome mostrato ai clienti'],
		['name' => 'name_it', 'text' => 'Nome mostrato ai clienti'],
	],
	[
		['type' => 'lbl', 'text' => 'Nome plurale mostrato ai clienti'],
		['name' => 'names_it', 'text' => 'Nome plurale mostrato ai clienti'],
	],
	[
		['type' => 'lbl', 'text' => 'Genere'],
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
$typeSizes = explode(',', DB::find('sizes FROM items_types WHERE ID = ?', [$id]));

$res = DB::select('tag, name FROM items_sizes ORDER BY ID ASC');

while ($row = $res->fetch_assoc()) {

	$size = ['type' => 'cbx', 'name' => 'sizes[]', 'value' => $row['tag'], 'ignore' => true, 'text' => $row['name'], 'size' => 1];

	if (in_array($row['tag'], $typeSizes)) $size['checked'] = true;

	$array[] = $size;
}

$inputs[] = $array;

//positions
$array = [['type' => 'lbl', 'text' => 'Posizioni di stampa']];
$typePositions = explode(',', DB::find('positions FROM items_types WHERE ID = ?', [$id]));

$res = DB::select('ID, name_it FROM items_positions ORDER BY ID ASC');

while ($row = $res->fetch_assoc()) {

	$size = ['type' => 'cbx', 'name' => 'positions[]', 'value' => $row['ID'], 'ignore' => true, 'text' => $row['name_it'], 'size' => 1];

	if (in_array($row['ID'], $typePositions)) $size['checked'] = true;

	$array[] = $size;
}

$inputs[] = $array;

// Add the type
FormController::setAdd(function () {
	// Create the type
	$type =	ProductType::create(
		[
			'tag' => $_POST['tag'],
			'name' => $_POST['name'],
			'name_it' => $_POST['name_it'],
			'names_it' => $_POST['names_it'],
			'gender' => $_POST['gender'],
			'sibling' => $_POST['sibling'],
			'unisex' => $_POST['unisex'] ?? 0,
			'sizes' => implode(',', $_POST['sizes']),
			'positions' => implode(',', $_POST['positions']),
		]
	);

	// Create the base price
	BasePrice::create(['typeID' => $type->ID]);

	// Create the measures
	if (isset($_POST['sizes'])) {
		foreach ($_POST['sizes'] as $size) {
			ProductTypeMeasure::create([
				'typeId' => $type->ID,
				'size' => $size
			]);
		}
	}

	// Create the mockups
	if (isset($_POST['positions'])) {
		foreach ($_POST['positions'] as $position) {
			ProductTypeMockup::create([
				'typeID' => $type->ID,
				'positionID' => $position
			]);
		}
	}

	$designs = Design::where('status', 1);

	foreach ($designs as $design) {
		Product::new($design->ID, $type->ID);
	}
});

//edit
function _expand_edit($tbl, $inputs, $index, $id) {
	form_db_update($tbl, $inputs, $index, $id);
}
