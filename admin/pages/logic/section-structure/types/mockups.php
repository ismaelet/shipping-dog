<?php

use Ecommerce\Product\Color;
use Ecommerce\Product\Mockup;
use Ecommerce\Product\Type;

$typeId = $_GET['id'];
$type = new Type($typeId);

if (!$type->id) redirect('list');

View::setTitle('Mockup ' . $type->fullName);

$mockups = [];

foreach ($type->colors as $color) {
	foreach ($type->positions as $position) {
		if ($position->hasSpecificMockup) {
			$mockups[] = new Mockup($type->id, $color->id, $position->id);
		}
	}
}

/*
* Image upload
*/
if (isset($_POST['image-url'])) {
	$typeId = $_POST['type-id'];
	$colorId = $_POST['color-id'];
	$positionId = $_POST['position-id'];

	$type =  new Type($typeId);
	$color =  new Color($colorId);

	$fileData = [
		'url' => public_server_path($_POST['image-url']),
		'size' => 1024,
		'quality' => 100,
	];

	upload_img($fileData);

	$files = glob(public_server_path('/uploads/products/' . $type->slug . '/*-' . $color->slug . '-' . $positionId . '-*'));
	array_map('unlink', $files);

	redirect('#' . $colorId . '-' . $positionId);
}
