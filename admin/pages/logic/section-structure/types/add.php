<?php

use App\Http\Controllers\Admin\FormController;
use App\Models\Ecommerce\BasePrice;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductTypeMeasure;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Product\ProductTypeMockup;
use Ecommerce\Product as OldProduct;

global $id;

$productType = $id ? ProductType::find($id) : null;

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

// Colors
$array = [['type' => 'lbl', 'text' => 'Colori']];
$typeColors = $productType ? $productType->colorCollection()->pluck('tag')->toArray() : [];
$colors = ProductColor::all()->sortBy('ID')->sortBy('reorder');

foreach ($colors as $color) {
	$colorInput = [
		'type' => 'cbx',
		'name' => 'colors[]',
		'value' => $color->tag,
		'ignore' => true,
		'text' => $color->name_it,
		'size' => 1
	];

	if ($productType && in_array($color->tag, $typeColors)) {
		$colorInput['checked'] = true;
	}

	$array[] = $colorInput;
}

$inputs[] = $array;

// Sizes
$array = [['type' => 'lbl', 'text' => 'Taglie']];
$typeSizes = $productType ? $productType->sizeCollection()->pluck('tag')->toArray() : [];
$sizes = ProductSize::all()->sortBy('ID');

foreach ($sizes as $size) {
	$sizeInput = [
		'type' => 'cbx',
		'name' => 'sizes[]',
		'value' => $size->tag,
		'ignore' => true,
		'text' => $size->name,
		'size' => 1
	];

	if ($productType && in_array($size->tag, $typeSizes)) {
		$sizeInput['checked'] = true;
	}

	$array[] = $sizeInput;
}

$inputs[] = $array;

// Positions
$array = [['type' => 'lbl', 'text' => 'Posizioni di stampa']];
$typePositions = $productType ? $productType->positionCollection()->pluck('ID')->toArray() : [];
$positions = ProductPosition::all()->sortBy('ID');

foreach ($positions as $position) {
	$size = [
		'type' => 'cbx',
		'name' => 'positions[]',
		'value' => $position->ID,
		'ignore' => true,
		'text' => $position->name_it,
		'size' => 1
	];

	if ($productType && in_array($position->ID, $typePositions)) {
		$size['checked'] = true;
	}

	$array[] = $size;
}

$inputs[] = $array;

$inputs[] = [
	['type' => 'lbl', 'text' => 'Stato'],
	[
		'type' => 'cbx',
		'name' => 'public',
		'checked' => $productType ? !$productType->hidden : false,
		'ignore' => true,
		'text' => 'Il tipo di prodotto è attivo',
	]
];

// Add the type
FormController::setAdd(function () {
	$public = isset($_POST['public']);

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
			'colors' => implode(',', $_POST['colors']),
			'sizes' => implode(',', $_POST['sizes']),
			'positions' => implode(',', $_POST['positions']),
			'hidden' => !$public,
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

	$designs = Design::where('status', 1)->get();

	if ($public) {
		foreach ($designs as $design) {
			OldProduct::new($design->ID, $type->ID);
		}
	}
});

// Edit the type
FormController::setEdit(function ($id) {
	$productType = ProductType::find($id);
	$oldStatus = !$productType->hidden;
	$public = isset($_POST['public']);
	$oldColors = $productType->colorCollection()->pluck('tag')->toArray();
	$newColors = $_POST['colors'];
	$colorsToAdd = array_diff($newColors, $oldColors);
	$colorsToRemove = array_diff($oldColors, $newColors);

	// Create the type
	$productType->update(
		[
			'tag' => $_POST['tag'],
			'name' => $_POST['name'],
			'name_it' => $_POST['name_it'],
			'names_it' => $_POST['names_it'],
			'gender' => $_POST['gender'],
			'sibling' => $_POST['sibling'],
			'unisex' => $_POST['unisex'] ?? 0,
			'colors' => implode(',', $_POST['colors']),
			'sizes' => implode(',', $_POST['sizes']),
			'positions' => implode(',', $_POST['positions']),
			'hidden' => !$public,
		]
	);

	// Add the new colors
	foreach ($colorsToAdd as $color) {
		DB::update((new Product)->getTable() . " SET colors = CONCAT(colors, ',$color') WHERE typeID = ?", [$productType->ID]);
		DB::update((new Product)->getTable() . " SET colors = TRIM(LEADING ',' FROM colors) WHERE typeID = ?", [$productType->ID]);
	}

	// Remove the old colors
	foreach ($colorsToRemove as $color) {
		DB::update((new Product)->getTable() . " SET colors = CONCAT(',', colors, ',') WHERE typeID = ?", [$productType->ID]);
		DB::update((new Product)->getTable() . " SET colors = REPLACE(colors, ',$color,', ',') WHERE typeID = ?", [$productType->ID]);
		DB::update((new Product)->getTable() . " SET colors = TRIM(BOTH ',' FROM colors) WHERE typeID = ?", [$productType->ID]);
	}

	if (!$oldStatus && $public) {
		$designs = Design::where('status', 1)->get();

		foreach ($designs as $design) {
			if (!Product::where('desID', $design->ID)->where('typeID', $productType->ID)->count()) {
				OldProduct::new($design->ID, $productType->ID);
			}
		}
	}
});
