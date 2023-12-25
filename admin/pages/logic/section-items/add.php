<?php

use Ecommerce\Product;
use Ecommerce\Product\Color;
use Ecommerce\Product\Type;

$id = $_GET['id'] ?? null;
$product = $id ? new Product($id) : null;

$pageConfig['free'] = true;
$queryConfig['table'] = 'items';
$queryConfig['index-field'] = 'ID';
$queryConfig['title-field'] = 'name';

// seller input
$sellerInput = [
	'search' => 'sellers',
	'name' => 'sellerId',
	'text' => 'Negozio',
	'name-show' => null,
	'value' => $product ? $product->sellerId : null,
	'value-show' => $product ? $product->seller->name : null,
	'required' => true,
];

// fetch types
$types = [];
$typeRows = DB::select('ID FROM items_types ORDER BY category ASC, ID ASC');

while ($typeRow = $typeRows->fetch_assoc()) {
	$type = new Type($typeRow['ID']);
	$types[] = $type;
}

// fetch colors
$colors = [];
$colorRows = DB::select('ID FROM items_colors ORDER BY reorder ASC, ID ASC');

while ($colorRow = $colorRows->fetch_assoc()) {
	$color = new Color($colorRow['ID']);
	$colors[] = $color;
}

// image upload
if (isset($_POST['upload'])) {
	$colorId = $_POST['upload'];

	$files = [
		['size' => 1024, 'quality' => 90, 'subfolder' => null],
		['size' => 400, 'quality' => 80, 'subfolder' => null],
		['size' => 1024, 'quality' => 90, 'subfolder' => '_custom'],
		['size' => 400, 'quality' => 80, 'subfolder' => '_custom'],
	];

	foreach ($files as $file) {
		$url = PUBLIC_SERVER_ROOT . $product->setColorId($colorId)->image($file['size'], 1);

		if ($file['subfolder']) {
			$url = str_replace('/items', '/items/_custom', $url);
		}

		$imageData = [
			'mode' => 'max',
			'name' => 'file-' . $colorId,
			'size' => $file['size'],
			'quality' => $file['quality'],
			'url' => $url
		];

		upload_img($imageData);
	}
}

// add
function _expand_add($table) {
	$name = $_POST['name'];
	$sellerId = $_POST['sellerId'];
	$typeId = $_POST['typeId'];
	$price = str_replace(',', '.', $_POST['price']);
	$defaultColorId = $_POST['default-color-id'];
	$colors = implode(',', $_POST['colors']);

	$productId = DB::add($table . ' (name, desID, userID, typeID, colorID, colors, positions, status)', [$name, 0, $sellerId, $typeId, $defaultColorId, $colors, 1, 1]);

	DB::add('items_prices (itemID, price)', [$productId, $price]);
}

// edit
function _expand_edit($table, $inputs, $index, $id) {
	$name = $_POST['name'];
	$sellerId = $_POST['sellerId'];
	$typeId = $_POST['typeId'];
	$price = str_replace(',', '.', $_POST['price']);
	$defaultColorId = $_POST['default-color-id'];
	$colors = implode(',', $_POST['colors']);

	DB::update($table . ' SET name = ?, userID = ?, typeID = ?, colorID = ?, colors=? WHERE ID = ?', [$name, $sellerId, $typeId, $defaultColorId, $colors, $id]);

	if (DB::exists('items_prices WHERE itemID = ?', [$id])) {
		DB::update('items_prices SET price=? WHERE itemID = ?', [$price, $id]);
	} else {
		DB::add('items_prices (itemID, price)', [$id, $price]);
	}

	if ($_FILES['cover-image']['name']) {
		$product = new Product($id);

		$imageData = [
			'mode' => 'max',
			'name' => 'cover-image',
			'size' => 200,
			'quality' => 80,
			'url' => PUBLIC_SERVER_ROOT . $product->coverImage
		];

		upload_img($imageData);
	}
}
