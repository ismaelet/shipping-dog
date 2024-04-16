<?php

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use Ecommerce\Product as OldProduct;
use Ecommerce\Product\Color;
use Ecommerce\Product\Type;

$productId = $_GET['id'] ?? null;
$oldProduct = $productId ? new OldProduct($productId) : null;
$product = Product::find($productId);
$seller = ViewOld::get('seller');
$saved = $_GET['saved'] ?? false;

// fetch types
$types = [];
$typeRows = DBOld::select('id FROM items_types WHERE id<>16 ORDER BY category ASC, id ASC');

while ($typeRow = $typeRows->fetch_assoc()) {
	$type = new Type($typeRow['id']);
	$types[] = $type;
}

// fetch colors
$colors = [];
$colorRows = DBOld::select('id FROM items_colors ORDER BY reorder ASC, id ASC');

while ($colorRow = $colorRows->fetch_assoc()) {
	$color = new Color($colorRow['id']);
	$colors[] = $color;
}

// add
if (isset($_POST['add']) || isset($_POST['edit'])) {
	$name = $_POST['name'];
	$type_id = $_POST['type_id'];
	$type = ProductType::find($type_id);
	$price = str_replace(',', '.', $_POST['price']);
	$description = $_POST['description'];
	$defaultColorId = $_POST['default-color-id'];
	$newColors = implode(',', $_POST['colors'] ?? [$defaultColorId]);
	$position_id = $type->positions ? explode(',', $type->positions)[0] : null;

	if (isset($_POST['add'])) {
		// add
		$product = Product::create([
			'name' => $name,
			'design_id' => 0,
			'user_id' => $seller->id,
			'type_id' => $type_id,
			'color_id' => $defaultColorId,
			'colors' => $newColors,
			'status' => 1,
			'positions' => $position_id,
		]);

		$oldProduct = new OldProduct($product->id);

		// upload product images
		if (!empty($_POST['colors'])) {
			foreach ($_POST['colors'] as $colorSlug) {
				$colorId = DBOld::find('id FROM items_colors WHERE tag = ?', [$colorSlug]);

				if ($_FILES['file-' . $colorId]['name']) {
					upload_product_image($oldProduct, $colorId);
				}
			}
		}
	} else {
		// edit
		$product->update([
			'name' => $name,
			'type_id' => $type_id,
			'color_id' => $defaultColorId,
			'colors' => $newColors
		]);
	}

	DBOld::put('items_info', ['description' => $description], $oldProduct->id);

	// Price
	ProductPrice::updateOrCreate(['product_id' => $product->id], [
		'price' => $price,
		'gain_w' => $oldProduct->whiteGain,
		'gain_c' => $oldProduct->coloredGain
	]);

	// Update cover image
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

	redirect_old('?id=' . $oldProduct->id . '&saved=1');
}

// delete
if (isset($_POST['delete'])) {
	$oldProduct->delete();

	redirect_old('list');
}

// image upload
if (isset($_POST['upload'])) {
	$colorId = $_POST['upload'];
	$color = ProductColor::find($colorId);
	$colors = explode(',', $product->colors);

	if (!in_array($color->tag, $colors)) {
		$colors[] = $color->tag;
		$product->colors = implode(',', $colors);
		$product->save();
	}

	upload_product_image($oldProduct, $colorId);

	redirect_old('#active-colors');
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
