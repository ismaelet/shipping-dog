<?php

use Ecommerce\Product;
use Ecommerce\Product\Color;
use Ecommerce\Product\Type;

$productId = $_GET['id'] ?? null;
$product = $productId ? new Product($productId) : null;
$seller = View::get('seller');
$saved = $_GET['saved'] ?? false;

// fetch types
$types = [];
$typeRows = DB::select('ID FROM items_types WHERE id<>16 ORDER BY category ASC, ID ASC');

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

// add
if (isset($_POST['add']) || isset($_POST['edit'])) {
	$name = $_POST['name'];
	$typeId = $_POST['typeId'];
	$price = str_replace(',', '.', $_POST['price']);
	$description = $_POST['description'];
	$defaultColorId = $_POST['default-color-id'];
	$newColors = implode(',', $_POST['colors'] ?? [$defaultColorId]);

	if (isset($_POST['add'])) {
		// add
		$productId = DB::add('items (name, desID, userID, typeID, colorID, colors, positions, status)', [$name, 0, $seller->id, $typeId, $defaultColorId, $newColors, 1, 0]);
		$product = new Product($productId);

		// upload product images
		if (!empty($_POST['colors'])) {
			foreach ($_POST['colors'] as $colorSlug) {
				$colorId = DB::find('ID FROM items_colors WHERE tag = ?', [$colorSlug]);

				if ($_FILES['file-' . $colorId]['name']) {
					upload_product_image($product, $colorId);
				}
			}
		}
	} else {
		// edit
		DB::update('items SET name = ?, typeID = ?, colorID = ?, colors = ? ', [$name, $typeId, $defaultColorId, $newColors, $product->id]);
	}

	DB::put('items_info', ['description' => $description], $product->id);

	// price
	if (!DB::exists('items_prices WHERE itemID = ?', [$product->id])) {
		DB::add('items_prices (itemID, price, gain_w, gain_c)', [$product->id, $product->fullPrice, $product->whiteGain, $product->coloredGain]);
	}

	$product->updatePrice($price);

	// update cover image
	if ($_FILES['cover-image']['name']) {
		$imageData = [
			'mode' => 'max',
			'name' => 'cover-image',
			'size' => 2000,
			'quality' => 80,
			'url' => SERVER_ROOT . $product->coverImage
		];

		upload_img($imageData);
	}

	redirect('?id=' . $product->id . '&saved=1');
}

// delete
if (isset($_POST['delete'])) {
	$product->delete();

	redirect('list');
}

// image upload
if (isset($_POST['upload'])) {
	$colorId = $_POST['upload'];

	upload_product_image($product, $colorId);

	redirect('#active-colors');
}

function upload_product_image($product, $colorId) {
	$files = [
		['size' => 1024, 'quality' => 90],
		['size' => 400, 'quality' => 80],
	];

	foreach ($files as $file) {
		$url = $product->setColorId($colorId)->getServerSpecificImage($file['size']);

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
