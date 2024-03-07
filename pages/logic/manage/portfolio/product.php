<?php

use App\Models\Ecommerce\Product;
use Ecommerce\Product as OldProduct;
use Ecommerce\Product\Color;
use Ecommerce\Product\Type;

$productId = $_GET['id'] ?? null;
$oldProduct = $productId ? new OldProduct($productId) : null;
$product = Product::find($productId);
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
		$product = new Product();
		$product->name = $name;
		$product->desID = 0;
		$product->userID = $seller->id;
		$product->typeID = $typeId;
		$product->colorID = $defaultColorId;
		$product->colors = $newColors;
		$product->status = 1;
		$product->positions = 0;
		$product->save();

		$oldProduct = new OldProduct($product->ID);

		// upload product images
		if (!empty($_POST['colors'])) {
			foreach ($_POST['colors'] as $colorSlug) {
				$colorId = DB::find('ID FROM items_colors WHERE tag = ?', [$colorSlug]);

				if ($_FILES['file-' . $colorId]['name']) {
					upload_product_image($oldProduct, $colorId);
				}
			}
		}
	} else {
		// edit
		$product->update([
			'name' => $name,
			'typeID' => $typeId,
			'colorID' => $defaultColorId,
			'colors' => $newColors
		]);
	}

	DB::put('items_info', ['description' => $description], $oldProduct->id);

	// price
	if (!DB::exists('items_prices WHERE itemID = ?', [$oldProduct->id])) {
		DB::add('items_prices (itemID, price, gain_w, gain_c)', [$oldProduct->id, $oldProduct->fullPrice, $oldProduct->whiteGain, $oldProduct->coloredGain]);
	}

	$oldProduct->updatePrice($price);

	// update cover image
	if ($_FILES['cover-image']['name']) {
		$imageData = [
			'mode' => 'max',
			'name' => 'cover-image',
			'size' => 2000,
			'quality' => 80,
			'url' => SERVER_ROOT . $oldProduct->coverImage
		];

		upload_img($imageData);
	}

	redirect('?id=' . $oldProduct->id . '&saved=1');
}

// delete
if (isset($_POST['delete'])) {
	$oldProduct->delete();

	redirect('list');
}

// image upload
if (isset($_POST['upload'])) {
	$colorId = $_POST['upload'];

	upload_product_image($oldProduct, $colorId);

	redirect('#active-colors');
}

function upload_product_image($oldProduct, $colorId) {
	$files = [
		['size' => 1024, 'quality' => 90],
		['size' => 400, 'quality' => 80],
	];

	foreach ($files as $file) {
		$url = $oldProduct->setColorId($colorId)->getServerSpecificImage($file['size']);

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
