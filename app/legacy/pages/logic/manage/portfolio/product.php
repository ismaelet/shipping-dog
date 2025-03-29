<?php

use App\Helpers\ImageHelper;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductDescription;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;

$productId = $_GET['id'] ?? null;
$product = Product::find($productId);
$saved = $_GET['saved'] ?? false;

// Fetch types
$types = ProductType::where('id', '<>', 16)->orderBy('sort')->get();

// Fetch colors
$colors = ProductColor::orderBy('reorder')->get();

// Add
if (isset($_POST['add']) || isset($_POST['edit'])) {
	$name = $_POST['name'];
	$typeId = $_POST['type_id'];
	$type = ProductType::find($typeId);
	$price = str_replace(',', '.', $_POST['price']);
	$description = $_POST['description'];
	$defaultColorId = $_POST['default-color-id'];
	$newColors = implode(',', $_POST['colors'] ?? [$defaultColorId]);
	$positionId = $type->positions ? explode(',', $type->positions)[0] : null;

	if (isset($_POST['add'])) {
		// add
		$product = Product::create([
			'name' => $name,
			'design_id' => 0,
			'user_id' => $seller->id,
			'type_id' => $typeId,
			'color_id' => $defaultColorId,
			'colors' => $newColors,
			'status' => 1,
			'positions' => $positionId,
		]);

		// upload product images
		if (!empty($_POST['colors'])) {
			foreach ($_POST['colors'] as $color) {
				$colorId = \Legacy\System\Classes\LegacyDB::find('id FROM product_colors WHERE tag = ?', [$color]);

				if ($_FILES['file-' . $colorId]['name']) {
					upload_product_image($product, $colorId);
				}
			}
		}
	} else {
		// edit
		$product->update([
			'name' => $name,
			'type_id' => $typeId,
			'color_id' => $defaultColorId,
			'colors' => $newColors
		]);
	}

	ProductDescription::updateOrCreate(
		['product_id' => $product->id],
		['description' => $description]
	);

	// Price
	ProductPrice::updateOrCreate(
		['product_id' => $product->id],
		[
			'price' => $price,
			'gain_w' => $product->price()->gain_w,
			'gain_c' => $product->price()->gain_c,
		]
	);

	// Update cover image
	if ($_FILES['cover-image']['name']) {
		ImageHelper::upload(
			file: request()->file('cover-image'),
			path: 'products/custom/' . $this->id . '-cover.webp',
			size: 1920,
			mode: 'width',
			quality: 80
		);
	}

	redirect_old('?id=' . $product->id . '&saved=1');
}

// delete
if (isset($_POST['delete'])) {
	$product->delete();

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

	upload_product_image($product, $colorId);

	redirect_old('#active-colors');
}

function upload_product_image(Product $product, int $colorId) {
	$color = ProductColor::find($colorId);

	$files = [
		['size' => 1024, 'quality' => 90],
		['size' => 400, 'quality' => 80],
	];

	foreach ($files as $file) {
		ImageHelper::upload(
			file: request()->file('file-' . $colorId),
			path: 'products/custom/' . $product->id . '-' . $color->tag . '-1-' . $file['size'] . '.webp',
			size: $file['size'],
			mode: 'largest',
			quality: $file['quality']
		);
	}
}
