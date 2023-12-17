<?php

use Ecommerce\Order;
use Ecommerce\Product;

// get variabiles
$id = $_GET['id'] ?? null;
$productPositionId = $_GET['positionId'] ?? null;
$productColorId = $_GET['colorId'] ?? null;

// select item data
$product = new Product($id);

// if not found
if (!$product->id || (!$product->statusId && !$product->isUserOwner) || $product->deleted) {
	redirect('/shop');
}

if ($productPositionId) {
	$product->setPositionId($productPositionId);
}

if ($productColorId) {
	$product->setColorId($productColorId);
}

View::setThumbnail($product->clientFullImage)
	->setTitle($product->name);

// get item seller
$seller = $product->seller;

// redirect if not public
$owner = $product->isUserOwner;
$vip = $owner || admin() ? true : false;

$wip = !$seller->status;
if ($wip && !$vip) redirect('/shop');

// select following status
$foll = DB::exists('followers WHERE userID = ? AND followID = ?', [userId(), $product->seller->id]);

// select fan pics
$res_fan = DB::select('* FROM home_pics WHERE desID = ? ORDER BY ID DESC LIMIT ?', [$product->design->id, 5]);
$fanN = $res_fan->num_rows;

/*
* Related products
*/
if (!$product->type->genderId || $product->type->genderId == 3) {
	$relatedGarments = rows_to_objects(
		DB::select(
			"items.ID FROM items
		INNER JOIN items_types ON items.typeID = items_types.ID
		WHERE desID = ? AND typeID <> ? AND category='c' AND items.status=1 AND items.deleted = 0
		ORDER BY items.sort ASC, items_types.sort ASC, items_types.ID ASC",
			[$product->design->id, $product->type->id]
		),
		Product::class
	);
} else {
	$relatedGarments = rows_to_objects(
		DB::select(
			"items.ID FROM items
		INNER JOIN items_types ON items.typeID = items_types.ID
		WHERE desID = ? AND typeID <> ? AND category='c' AND (gender = ? OR gender=0 OR gender=3) AND items.status=1 AND items.deleted = 0
		ORDER BY items.sort ASC, items_types.sort ASC, items_types.ID ASC",
			[$product->design->id, $product->type->id, $product->type->genderId]
		),
		Product::class
	);
}

$relatedAccessories = rows_to_objects(DB::select("ID FROM items WHERE desID = ? AND typeID <> ? AND typeID IN (SELECT ID FROM items_types WHERE category='m') AND status=1 ORDER BY typeID ASC", [$product->design->id, $product->type->id]), Product::class);

$relatedProdutsVariables = [$seller->id, $product->design->id, $product->type->id, 8];
$relatedProdutsVariables = array_merge($relatedProdutsVariables, $relatedProdutsVariables);

$relatedProducts = rows_to_objects(
	DB::execute(
		'(SELECT ID FROM items WHERE userID = ? AND (desID <> ? OR desID = 0) AND status > 0 AND typeID = ? ORDER BY sales DESC, ID DESC LIMIT ?)
		UNION
		(SELECT ID FROM items WHERE userID = ? AND (desID <> ? OR desID = 0) AND status > 0 AND typeID <> ? ORDER BY sales DESC, ID DESC LIMIT ?)',
		$relatedProdutsVariables
	)->get_result(),
	Product::class
);

$relatedProducts = $relatedProducts ? array_slice($relatedProducts, 0, 8) : $relatedProducts;

$relatedGarmentCount = count($relatedGarments);
$relatedAccessoryCount = count($relatedAccessories);

// warnings
$warning = warning_banner();

// add order
if (isset($_POST['submit'])) {
	$config['itemId'] = $id;
	$config['quantity'] = $_POST['quantity'];

	if (isset($_POST['cbx-color'])) foreach ($_POST['cbx-color'] as $config['colorId']);
	if (isset($_POST['cbx-size'])) foreach ($_POST['cbx-size'] as $config['sizeId']);
	if (isset($_POST['cbx-gender'])) foreach ($_POST['cbx-gender'] as $config['genderId']);

	if (isset($_POST['logo-id'])) {
		$config['logoId'] = $_POST['logo-id'];
	}

	if (isset($_POST['cbx-pos'])) {
		foreach ($_POST['cbx-pos'] as $config['positionId']);
	} else {
		$config['positionId'] = $product->availablePositionIds[0];
	}

	Order::add($config);

	redirect('/cart');
}
