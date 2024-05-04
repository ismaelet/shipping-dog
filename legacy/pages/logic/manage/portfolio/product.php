<?php

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use Legacy\Classes\Ecommerce\Product as OldProduct;
use Legacy\Classes\Ecommerce\Product\Color as LegacyProductColor;
use Legacy\Classes\Ecommerce\Product\Type as LegacyProductType;

$productId = $_GET['id'] ?? null;
$oldProduct = $productId ? new OldProduct($productId) : null;
$product = Product::find($productId);
$seller = \Legacy\System\Classes\LegacyView::get('seller');
$saved = $_GET['saved'] ?? false;

// fetch types
$types = [];
$typeRows = \Legacy\System\Classes\LegacyDB::select('id FROM product_types WHERE id<>16 ORDER BY category ASC, id ASC');

while ($typeRow = $typeRows->fetch_assoc()) {
	$type = new Type($typeRow['id']);
	$types[] = $type;
}

// fetch colors
$colors = [];
$colorRows = \Legacy\System\Classes\LegacyDB::select('id FROM product_colors ORDER BY reorder ASC, id ASC');

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
				$color_id = \Legacy\System\Classes\LegacyDB::find('id FROM product_colors WHERE tag = ?', [$colorSlug]);

				if ($_FILES['file-' . $color_id]['name']) {
					upload_product_image($oldProduct, $color_id);
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

	\Legacy\System\Classes\LegacyDB::put('items_info', ['description' => $description], $oldProduct->id);

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
	$color_id = $_POST['upload'];
	$color = ProductColor::find($color_id);
	$colors = explode(',', $product->colors);

	if (!in_array($color->tag, $colors)) {
		$colors[] = $color->tag;
		$product->colors = implode(',', $colors);
		$product->save();
	}

	upload_product_image($oldProduct, $color_id);

	redirect_old('#active-colors');
}

function upload_product_image($oldProduct, $color_id) {
	$files = [
		['size' => 1024, 'quality' => 90],
		['size' => 400, 'quality' => 80],
	];

	foreach ($files as $file) {
		$url = $oldProduct->setColorId($color_id)->getServerSpecificImage($file['size']);

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
