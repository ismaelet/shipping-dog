<?php

use App\Helpers\FileHelper;
use App\Models\Ecommerce\Design;
use Legacy\Classes\Ecommerce\Design as LegacyDesign;
use Legacy\Classes\Ecommerce\Product as LegacyProduct;
use Legacy\Classes\Ecommerce\Product\Color as LegacyProductColor;
use Legacy\Classes\Ecommerce\Product\PreviewOld as LegacyPreview;
use Legacy\Classes\Ecommerce\Product\Type as LegacyProductType;

// setup page
$error = $_SESSION['error'] ?? false;
$done = $_SESSION['done'] ?? false;

unset($_SESSION['error']);
unset($_SESSION['done']);

$id = $_GET['id'] ?? false;

$design = Design::where('id', $id)
	->where('user_id', auth()->id())
	->first();

if (!$design) {
	redirect_old('/manage/portfolio');
}

$seller = $design->seller;

\Legacy\System\Classes\LegacyView::$title = 'Modifica: ' . $design->name;

$custom = $design->custom;

// generate data
$res_colorD = \Legacy\System\Classes\LegacyDB::select('id FROM items_colors WHERE hidden = 0 ORDER BY hidden ASC, preorder ASC, reorder ASC, name ASC');

// reupload design
if (isset($_POST['upload-light']) || isset($_POST['upload-dark'])) {

	$dark = isset($_POST['upload-dark']);
	$type = $dark ? '_dark' : '';

	// upload image
	$file = 'designs/' . $id . $type . '.png';
	$file_m = 'designs/' . $id . $type . '_m.png';
	$file_ori = 'designs_originals/' . $id . $type . '.png';

	// Store the original file
	$newFile = FileHelper::uploadFile(request(), $file_ori);

	// Store the usable HD file
	$newFile = FileHelper::uploadImage([
		'path' => $file,
		'size' => 1024,
		'type' => 'png',
	]);

	// Store the usable LD file
	$newFile = FileHelper::uploadImage([
		'path' => $file_m,
		'size' => 512,
		'type' => 'png',
	]);

	$reso = getimagesize($_FILES['file']['tmp_name']);
	$ratio = round($reso[0] / $reso[1], 2);

	// update ratio
	\Legacy\System\Classes\LegacyDB::update('designs SET ratio=? WHERE id = ?', [$ratio, $id]);

	if (!$design->custom) {
		// delete item images
		$res = \Legacy\System\Classes\LegacyDB::select('id from items WHERE design_id = ?', [$id]);
		while ($row = $res->fetch_assoc()) LegacyPreview::deleteImages($row['id']);
	}

	// delete design images
	if (file_exists($file = public_storage('/designs/previews/' . $id . '.jpg'))) unlink($file);
	if (file_exists($file = public_storage('/designs/previews/' . $id . '_m.jpg'))) unlink($file);

	if ($dark) redirect_old('#des-dark-anch');
	else redirect_old('#des-light-anch');
}

// upload vector
if (isset($_POST['upload-vector'])) {

	$type = strrev(explode('.', strrev($_FILES['file']['name']))[0]);

	$file = '/uploads/designs_vector/' . $id . '.' . $type;

	if (in_array(strtolower($type), ['pdf', 'ai']))
		upload_file($file);

	redirect_old('#des-vector-anch');
}

// update tags
if (isset($_POST['tags-submit'])) {

	update_tags($_POST['tags'], $id, $design->tags);

	// refresh
	$_SESSION['done'] = 'tags';

	redirect_old('#tags-anch');
}

// update colors
if (isset($_POST['colors-submit'])) {

	// get default color
	$color = new LegacyProductColor($_POST['colorD'][0]);

	$types = \Legacy\System\Classes\LegacyDB::select('id, colors FROM product_types');

	// update colors of items
	while ($row = $types->fetch_assoc()) {

		$type = new LegacyProductType($row['id']);

		$productID = \Legacy\System\Classes\LegacyDB::find('id FROM products WHERE design_id = ? AND type_id = ?', [$id, $type->id]);

		$product = new LegacyProduct($productID);

		if ($productID && $product->color->id != $color->id && in_array($color->slug, $type->availableColorSlugs)) {
			$colors = $product->availableColorSlugs;

			if (!in_array($color->slug, $product->availableColorSlugs)) $colors[] = $color->slug;

			$colors = implode(',', $colors);

			\Legacy\System\Classes\LegacyDB::update('products SET color_id = ?, colors=? WHERE id = ?', [$color->id, $colors, $product->id]);
		}
	}

	// update design
	if (file_exists($file = public_storage('/designs/previews/' . $id . '.jpg'))) unlink($file);
	if (file_exists($file = public_storage('/designs/previews/' . $id . '_m.jpg'))) unlink($file);

	$colorHex = $_POST['color-hex'];

	\Legacy\System\Classes\LegacyDB::update('designs SET color=? WHERE id = ?', [$colorHex, $id]);

	// refresh
	$_SESSION['done'] = 'colors';

	redirect_old('#colors-anch');
}

// update colors
if (isset($_POST['update-colors'])) {
	$product = new LegacyProduct($_POST['product_id']);

	// get available colors
	$data = [];

	if (!empty($_POST['colorA'])) {
		$product->updateColors($_POST['colorA']);
	}
	if (isset($_POST['colorD'])) {
		$product->updateColorId($_POST['colorD']);
	}

	// refresh
	//redirect_old('#item-' . $product->type->id);
}

// update position
if (isset($_POST['update-position'])) {

	$product = new LegacyProduct($_POST['product_id']);

	// update mockup
	if (!$custom) {

		$data = [
			'position_id' => $_POST['position_id'],
			'size' => $_POST['mockup-size'],
			'pos-x' => $_POST['mockup-x'],
			'pos-y' => $_POST['mockup-y'],
		];

		LegacyPreview::put($_POST['product_id'], $data);
	}

	// refresh
	redirect_old('#item-' . $product->type->id);
}

// disable item
if (isset($_POST['off']) || isset($_POST['on'])) {
	$type_id = $_POST['type_id'];
	$productId = \Legacy\System\Classes\LegacyDB::find('id FROM products WHERE design_id = ? AND type_id = ?', [$id, $type_id]);
	$activate = isset($_POST['on']);

	if ($productId) {
		$product = new LegacyProduct($productId);
		$product->updateStatus($activate);
	} else if ($activate) {
		LegacyProduct::new($id, $type_id);
	}

	redirect_old('#item-' . $type_id);
}

// delete design
if (isset($_POST['delete'])) {
	delete_design($id);

	redirect_old('/manage/portfolio?id=' . $seller->id);
}
