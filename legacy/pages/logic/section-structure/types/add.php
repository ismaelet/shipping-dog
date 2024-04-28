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
use App\Models\Ecommerce\Product\ProductTypeInfo;
use App\Models\Ecommerce\Product\ProductTypeMockup;
use Legacy\Classes\Ecommerce\Product as OldProduct;

global $id;

$productType = $id ? ProductType::find($id) : null;

/**
 * Query configuration
 */
$queryConfig['table'] = 'items_types';
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
		['type' => 'lbl', 'text' => 'Nome per gli amministratori'],
		['name' => 'name', 'text' => 'Nome per gli amministratori'],
	],
	[
		['type' => 'lbl', 'text' => 'Nome mostrato ai clienti'],
		['name' => 'name', 'text' => 'Nome mostrato ai clienti'],
	],
	[
		['type' => 'lbl', 'text' => 'Nome plurale mostrato ai clienti'],
		['name' => 'names', 'text' => 'Nome plurale mostrato ai clienti'],
	],
	[
		['type' => 'lbl', 'text' => 'Descrizione'],
		['type' => 'textarea', 'name' => 'description', 'text' => 'Breve descrizione del prodotto', 'value' => $productType && $productType->info ? $productType->info->description : ''],
	],
	[
		['type' => 'lbl', 'text' => 'Dettagli'],
		['type' => 'textarea', 'name' => 'details', 'text' => 'Dettagli tecnici, separati da virgola', 'value' => $productType && $productType->info ? $productType->info->details : ''],
	],
	[
		['type' => 'lbl', 'text' => 'Categoria'],
		[
			'type' => 'select',
			'name' => 'category',
			'text' => 'Categoria',
			'array' => [['c', 'Abbigliamento'], ['m', 'Accessorio']],
		],
	],
	[
		['type' => 'lbl', 'text' => 'Immagine di copertina'],
		[
			'type' => 'file',
			'name' => 'cover_image',
		],
	],
	[
		['type' => 'lbl', 'text' => 'Genere'],
		[
			'type' => 'select',
			'name' => 'gender',
			'text' => 'Modello',
			'query' => 'ID, name FROM items_genders',
			'field-name' => 'id',
			'field-show' => 'name',
			'empty' => true,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Modello alternativo'],
		[
			'type' => 'select',
			'name' => 'sibling',
			'text' => 'Modello',
			'query' => 'ID, name FROM items_types ORDER BY id ASC',
			'field-name' => 'id',
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
$colors = ProductColor::all()->sortBy('id')->sortBy('reorder');

foreach ($colors as $color) {
	$colorInput = [
		'type' => 'cbx',
		'name' => 'colors[]',
		'value' => $color->tag,
		'ignore' => true,
		'text' => '<span class="color-box" style="--_color: #' . $color->color . ';"></span>' . $color->name,
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
$sizes = ProductSize::all()->sortBy('id');

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
$typePositions = $productType ? $productType->positionCollection()->pluck('id')->toArray() : [];
$positions = ProductPosition::all()->sortBy('id');

foreach ($positions as $position) {
	$size = [
		'type' => 'cbx',
		'name' => 'positions[]',
		'value' => $position->id,
		'ignore' => true,
		'text' => $position->name,
		'size' => 1
	];

	if ($productType && in_array($position->id, $typePositions)) {
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
			'name' => $_POST['name'],
			'names' => $_POST['names'],
			'category' => $_POST['category'],
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
	BasePrice::create(['type_id' => $type->id]);

	// Create the measures
	if (isset($_POST['sizes'])) {
		foreach ($_POST['sizes'] as $size) {
			ProductTypeMeasure::create([
				'type_id' => $type->id,
				'size' => $size
			]);
		}
	}

	// Create the mockups
	if (isset($_POST['positions'])) {
		foreach ($_POST['positions'] as $position) {
			ProductTypeMockup::create([
				'type_id' => $type->id,
				'position_id' => $position
			]);
		}
	}

	$designs = Design::where('status', 1)->get();

	if ($public) {
		foreach ($designs as $design) {
			OldProduct::new($design->id, $type->id);
		}
	}

	// Create the infos
	ProductTypeInfo::create([
		'product_type_id' => $type->id,
		'description' => $_POST['description'],
		'details' => $_POST['details'],
	]);
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
			'name' => $_POST['name'],
			'names' => $_POST['names'],
			'category' => $_POST['category'],
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
		\Legacy\System\Classes\LegacyDB::update((new Product)->getTable() . " SET colors = CONCAT(colors, ',$color') WHERE type_id = ?", [$productType->id]);
		\Legacy\System\Classes\LegacyDB::update((new Product)->getTable() . " SET colors = TRIM(LEADING ',' FROM colors) WHERE type_id = ?", [$productType->id]);
	}

	// Remove the old colors
	foreach ($colorsToRemove as $color) {
		\Legacy\System\Classes\LegacyDB::update((new Product)->getTable() . " SET colors = CONCAT(',', colors, ',') WHERE type_id = ?", [$productType->id]);
		\Legacy\System\Classes\LegacyDB::update((new Product)->getTable() . " SET colors = REPLACE(colors, ',$color,', ',') WHERE type_id = ?", [$productType->id]);
		\Legacy\System\Classes\LegacyDB::update((new Product)->getTable() . " SET colors = TRIM(BOTH ',' FROM colors) WHERE type_id = ?", [$productType->id]);
	}

	if (!$oldStatus && $public) {
		$designs = Design::where('status', 1)->get();

		foreach ($designs as $design) {
			if (!Product::where('design_id', $design->id)->where('type_id', $productType->id)->count()) {
				OldProduct::new($design->id, $productType->id);
			}
		}
	}

	// Update the infos
	ProductTypeInfo::updateOrCreate(
		['product_type_id' => $productType->id],
		[
			'description' => $_POST['description'],
			'details' => $_POST['details']
		]
	);

	// Update the cover image
	if (isset($_FILES['cover_image']) && $_FILES['cover_image']['size']) {
		$fileData = [
			'url' => public_server_path('/uploads/product_types/covers/' . $productType->tag),
			'size' => 2000,
			'name' => 'cover_image',
			'quality' => 80,
		];

		upload_img($fileData);
	}
});
