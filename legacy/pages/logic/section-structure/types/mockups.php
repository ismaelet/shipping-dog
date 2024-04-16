<?php

use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Product\ProductTypeMockup;
use Ecommerce\Product\Color;
use Ecommerce\Product\Mockup;

$type_id = $_GET['id'];
$type = ProductType::find($type_id);

if (!$type->id) {
	redirect_old('list');
}

ViewOld::setTitle('Mockup ' . $type->fullName);

$mockupData = ProductTypeMockup::getByTypeId($type_id);

$mockups = [];

foreach ($type->colorCollection() as $color) {
	foreach ($type->positionCollection() as $position) {
		if ($position->hasSpecificMockup()) {
			$mockups[] = new Mockup($type->id, $color->id, $position->id);
		}
	}
}

/**
 * Change mockup size and position
 */
if (isset($_POST['edit'])) {
	$mockupData->update([
		'pos_x_def' => $_POST['pos_x_def'],
		'pos_x_min' => $_POST['pos_x_min'],
		'pos_y_def' => $_POST['pos_y_def'],
		'pos_y_min' => $_POST['pos_y_min'],
		'siz_x_def' => $_POST['siz_x_def'],
		'siz_x_max' => $_POST['siz_x_max'],
		'siz_y_max' => $_POST['siz_y_max'],
	]);

	$files = glob(public_server_path('/uploads/products/' . $type->tag . '/*'));

	array_map('unlink', $files);
}

/*
* Image upload
*/
if (isset($_POST['image-url'])) {
	$type_id = $_POST['type-id'];
	$colorId = $_POST['color-id'];
	$position_id = $_POST['position-id'];

	$color =  new Color($colorId);

	$fileData = [
		'url' => public_server_path($_POST['image-url']),
		'size' => 1024,
		'quality' => 100,
	];

	upload_img($fileData);

	$files = glob(public_server_path('/uploads/products/' . $type->tag . '/*-' . $color->tag . '-' . $position_id . '-*'));

	array_map('unlink', $files);

	redirect_old('#' . $colorId . '-' . $position_id);
}
