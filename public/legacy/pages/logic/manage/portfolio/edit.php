<?php

use Ecommerce\Design;
use Ecommerce\Product;
use Ecommerce\Product\Color;
use Ecommerce\Product\Preview;
use Ecommerce\Product\Type;

// setup page
$error = $_SESSION['error'] ?? false;
$done = $_SESSION['done'] ?? false;

unset($_SESSION['error']);
unset($_SESSION['done']);

$id = $_GET['id'] ?? false;

if (!$row = DB::row('userID FROM designs WHERE ID = ?', [$id])) redirect('/manage/portfolio');

$design = new Design($id);
$seller = $design->seller;

if (!$design->userIsOwner) {
	redirect('/artist?id=' . $seller->id);
}


View::$title = 'Modifica: ' . $design->name;

$custom = $design->custom;

// generate data
$res_colorD = DB::select('ID FROM items_colors WHERE hidden = 0 ORDER BY hidden ASC, preorder ASC, reorder ASC, name_it ASC');

// reupload design
if (isset($_POST['upload-light']) || isset($_POST['upload-dark'])) {

	$dark = isset($_POST['upload-dark']);
	$type = $dark ? '_dark' : '';

	// upload image
	$file = '/uploads/designs/' . $id . $type . '.png';
	$file_m = '/uploads/designs/' . $id . $type . '_m.png';
	$file_ori = '/uploads/designs_originals/' . $id . $type . '.png';

	$reso = getimagesize($_FILES['file']['tmp_name']);
	$ratio = round($reso[0] / $reso[1], 2);

	$error = upload_img_OLD($file, 'png', '1024');
	$error_m = upload_img_OLD($file_m, 'png', '450');

	upload_file($file_ori);

	// update ratio
	if ($error) $_SESSION['error'] = $dark ? 'dark_' . $error : 'light_' . $error;
	else {

		DB::update('designs SET ratio=? WHERE ID = ?', [$ratio, $id]);

		if (!$design->custom) {
			// delete item images
			$res = DB::select('ID from items WHERE desID = ?', [$id]);
			while ($row = $res->fetch_assoc()) Preview::deleteImages($row['ID']);
		}

		// delete design images
		if (file_exists($file = SERVER_ROOT . '/uploads/designs/previews/' . $id . '.jpg')) unlink($file);
		if (file_exists($file = SERVER_ROOT . '/uploads/designs/previews/' . $id . '_m.jpg')) unlink($file);
	}

	if ($dark) redirect('#des-dark-anch');
	else redirect('#des-light-anch');
}

// upload vector
if (isset($_POST['upload-vector'])) {

	$type = strrev(explode('.', strrev($_FILES['file']['name']))[0]);

	$file = '/uploads/designs_vector/' . $id . '.' . $type;

	if (in_array(strtolower($type), ['pdf', 'ai']))
		upload_file($file);

	redirect('#des-vector-anch');
}

// update tags
if (isset($_POST['tags-submit'])) {

	update_tags($_POST['tags'], $id, $design->tags);

	// refresh
	$_SESSION['done'] = 'tags';

	redirect('#tags-anch');
}

// update colors
if (isset($_POST['colors-submit'])) {

	// get default color
	$color = new Color($_POST['colorD'][0]);

	$types = DB::select('ID, colors FROM items_types');

	// update colors of items
	while ($row = $types->fetch_assoc()) {

		$type = new Type($row['ID']);

		$productID = DB::find('ID FROM items WHERE desID = ? AND typeID = ?', [$id, $type->id]);

		$product = new Product($productID);

		if ($productID && $product->color->id != $color->id && in_array($color->slug, $type->availableColorSlugs)) {
			$colors = $product->availableColorSlugs;

			if (!in_array($color->slug, $product->availableColorSlugs)) $colors[] = $color->slug;

			$colors = implode(',', $colors);

			DB::update('items SET colorID = ?, colors=? WHERE ID = ?', [$color->id, $colors, $product->id]);
		}
	}

	// update design
	if (file_exists($file = SERVER_ROOT . '/uploads/designs/previews/' . $id . '.jpg')) unlink($file);
	if (file_exists($file = SERVER_ROOT . '/uploads/designs/previews/' . $id . '_m.jpg')) unlink($file);

	$colorHex = $_POST['color-hex'];

	DB::update('designs SET color=? WHERE ID = ?', [$colorHex, $id]);

	// refresh
	$_SESSION['done'] = 'colors';

	redirect('#colors-anch');
}

// update colors
if (isset($_POST['update-colors'])) {
	$product = new Product($_POST['itemID']);

	// get available colors
	$data = [];

	if (!empty($_POST['colorA'])) {
		$product->updateColors($_POST['colorA']);
	}
	if (isset($_POST['colorD'])) {
		$product->updateColorId($_POST['colorD']);
	}

	// refresh
	//redirect('#item-' . $product->type->id);
}

// update position
if (isset($_POST['update-position'])) {

	$product = new Product($_POST['itemID']);

	// update mockup
	if (!$custom) {

		$data = [
			'positionID' => $_POST['positionID'],
			'size' => $_POST['mockup-size'],
			'pos-x' => $_POST['mockup-x'],
			'pos-y' => $_POST['mockup-y'],
		];

		Preview::put($_POST['itemID'], $data);
	}

	// refresh
	redirect('#item-' . $product->type->id);
}

// disable item
if (isset($_POST['off']) || isset($_POST['on'])) {
	$typeId = $_POST['typeID'];
	$productId = DB::find('ID FROM items WHERE desID = ? AND typeID = ?', [$id, $typeId]);
	$activate = isset($_POST['on']);

	if ($productId) {
		$product = new Product($productId);
		$product->updateStatus($activate);
	} else if ($activate) {
		Product::new($id, $typeId);
	}

	redirect('#item-' . $typeID);
}

// delete design
if (isset($_POST['delete'])) {
	delete_design($id);

	redirect('/manage/portfolio?id=' . $seller->id);
}
