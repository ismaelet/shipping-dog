<?php

use App\Models\Ecommerce\Product;
use Ecommerce\Order;
use Ecommerce\Product as OldProduct;

// get variabiles
$id = $_GET['id'] ?? null;
$oldProductPositionId = $_GET['positionId'] ?? null;
$oldProductColorId = $_GET['colorId'] ?? null;

// select item data
$product = Product::find($id);
$oldProduct = new OldProduct($id);

// if not found
if (!$oldProduct->id || (!admin() && (!$oldProduct->statusId && !$oldProduct->isUserOwner) || $oldProduct->deleted)) {
	redirect('/shop');
}

if ($oldProductPositionId) {
	$oldProduct->setPositionId($oldProductPositionId);
}

if ($oldProductColorId) {
	$oldProduct->setColorId($oldProductColorId);
}

View::setThumbnail($oldProduct->clientFullImage)
	->setTitle($oldProduct->name);

// get item seller
$seller = $oldProduct->seller;

// redirect if not public
$owner = $oldProduct->isUserOwner;
$vip = $owner || admin() ? true : false;

$wip = !$seller->status;
if ($wip && !$vip) redirect('/shop');

// select following status
$foll = DB::exists('followers WHERE userID = ? AND followID = ?', [userId(), $oldProduct->seller->id]);

// select fan pics
$res_fan = DB::select('* FROM home_pics WHERE desID = ? ORDER BY ID DESC LIMIT ?', [$oldProduct->design->id, 5]);
$fanN = $res_fan->num_rows;

/*
* Related products
*/
if (!$oldProduct->type->genderId || $oldProduct->type->genderId == 3) {
	$relatedGarments = rows_to_objects(
		DB::select(
			"items.ID FROM items
		INNER JOIN items_types ON items.typeID = items_types.ID
		WHERE desID = ? AND typeID <> ? AND category='c' AND items.status = 1 AND items.deleted = 0
		ORDER BY items.sort ASC, items_types.sort ASC, items_types.ID ASC",
			[$oldProduct->design->id, $oldProduct->type->id]
		),
		OldProduct::class
	);
} else {
	$relatedGarments = rows_to_objects(
		DB::select(
			"items.ID FROM items
		INNER JOIN items_types ON items.typeID = items_types.ID
		WHERE desID = ? AND typeID <> ? AND category='c' AND (gender = ? OR gender=0 OR gender=3) AND items.status = 1 AND items.deleted = 0
		ORDER BY items.sort ASC, items_types.sort ASC, items_types.ID ASC",
			[$oldProduct->design->id, $oldProduct->type->id, $oldProduct->type->genderId]
		),
		OldProduct::class
	);
}

$relatedAccessories = rows_to_objects(DB::select("ID FROM items WHERE desID = ? AND typeID <> ? AND typeID IN (SELECT ID FROM items_types WHERE category='m') AND status = 1 ORDER BY typeID ASC", [$oldProduct->design->id, $oldProduct->type->id]), OldProduct::class);

$relatedProdutsVariables = [$seller->id, $oldProduct->design->id, $oldProduct->type->id, 8];
$relatedProdutsVariables = array_merge($relatedProdutsVariables, $relatedProdutsVariables);

$relatedProducts = rows_to_objects(
	DB::execute(
		'(SELECT ID FROM items WHERE userID = ? AND (desID <> ? OR desID = 0) AND status > 0 AND typeID = ? ORDER BY sales DESC, ID DESC LIMIT ?)
		UNION
		(SELECT ID FROM items WHERE userID = ? AND (desID <> ? OR desID = 0) AND status > 0 AND typeID <> ? ORDER BY sales DESC, ID DESC LIMIT ?)',
		$relatedProdutsVariables
	)->get_result(),
	OldProduct::class
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
		$config['positionId'] = $oldProduct->availablePositionIds[0];
	}

	Order::add($config);

	redirect('/cart');
}
