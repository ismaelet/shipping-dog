<?php

use Legacy\Classes\Ecommerce\Product;
use Legacy\Classes\Ecommerce\Product\Color;
use Legacy\Classes\Ecommerce\Product\Type;

$id = $_GET['id'] ?? null;
$product = $id ? new Product($id) : null;

$pageConfig['free'] = true;
$queryConfig['table'] = 'items';
$queryConfig['index-field'] = 'id';
$queryConfig['title-field'] = 'name';

// seller input
$sellerInput = [
	'search' => 'sellers',
	'name' => 'seller_id',
	'text' => 'Negozio',
	'name-show' => null,
	'value' => $product ? $product->user_id : null,
	'value-show' => $product ? $product->seller->name : null,
	'required' => true,
];

// fetch types
$types = [];
$typeRows = \Legacy\System\Classes\LegacyDB::select('id FROM product_types ORDER BY category ASC, id ASC');

while ($typeRow = $typeRows->fetch_assoc()) {
	$type = new Type($typeRow['id']);
	$types[] = $type;
}

// fetch colors
$colors = [];
$colorRows = \Legacy\System\Classes\LegacyDB::select('id FROM items_colors ORDER BY reorder ASC, id ASC');

while ($colorRow = $colorRows->fetch_assoc()) {
	$color = new Color($colorRow['id']);
	$colors[] = $color;
}

// image upload
if (isset($_POST['upload'])) {
	$color_id = $_POST['upload'];

	$files = [
		['size' => 1024, 'quality' => 90, 'subfolder' => null],
		['size' => 400, 'quality' => 80, 'subfolder' => null],
		['size' => 1024, 'quality' => 90, 'subfolder' => '_custom'],
		['size' => 400, 'quality' => 80, 'subfolder' => '_custom'],
	];

	foreach ($files as $file) {
		$url = PUBLIC_SERVER_ROOT . $product->setColorId($color_id)->image($file['size'], 1);

		if ($file['subfolder']) {
			$url = str_replace('/items', '/items/_custom', $url);
		}

		$imageData = [
			'mode' => 'max',
			'name' => 'file-' . $color_id,
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
	$seller_id = $_POST['seller_id'];
	$type_id = $_POST['type_id'];
	$price = str_replace(',', '.', $_POST['price']);
	$defaultColorId = $_POST['default-color-id'];
	$colors = implode(',', $_POST['colors']);

	$productId = \Legacy\System\Classes\LegacyDB::add($table . ' (name, design_id, user_id, type_id, color_id, colors, positions, status)', [$name, 0, $seller_id, $type_id, $defaultColorId, $colors, 1, 1]);

	\Legacy\System\Classes\LegacyDB::add('items_prices (product_id, price)', [$productId, $price]);
}

// edit
function _expand_edit($table, $inputs, $index, $id) {
	$name = $_POST['name'];
	$seller_id = $_POST['seller_id'];
	$type_id = $_POST['type_id'];
	$price = str_replace(',', '.', $_POST['price']);
	$defaultColorId = $_POST['default-color-id'];
	$colors = implode(',', $_POST['colors']);

	\Legacy\System\Classes\LegacyDB::update($table . ' SET name = ?, user_id = ?, type_id = ?, color_id = ?, colors=? WHERE id = ?', [$name, $seller_id, $type_id, $defaultColorId, $colors, $id]);

	if (\Legacy\System\Classes\LegacyDB::exists('items_prices WHERE product_id = ?', [$id])) {
		\Legacy\System\Classes\LegacyDB::update('items_prices SET price=? WHERE product_id = ?', [$price, $id]);
	} else {
		\Legacy\System\Classes\LegacyDB::add('items_prices (product_id, price)', [$id, $price]);
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
