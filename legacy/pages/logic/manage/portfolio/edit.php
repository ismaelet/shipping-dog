<?php


// reupload design
if (isset($_POST['upload-light']) || isset($_POST['upload-dark'])) {
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
