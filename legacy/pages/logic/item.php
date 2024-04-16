<?php

use App\Models\Ecommerce\Product;
use Ecommerce\Order;
use Ecommerce\Product as OldProduct;

// get variabiles
$id = $_GET['id'] ?? null;
$oldProductPositionId = $_GET['position_id'] ?? null;
$oldProductColorId = $_GET['colorId'] ?? null;

// select item data
$product = $id ? Product::find($id) : null;
$oldProduct = $id ? new OldProduct($id) : null;

// if not found
if (!$id || !$oldProduct->id || (!is_admin() && (!$oldProduct->statusId && !$oldProduct->isUserOwner) || $oldProduct->deleted)) {
	redirect_old('/shop');
}

if ($oldProductPositionId) {
	$oldProduct->setPositionId($oldProductPositionId);
}

if ($oldProductColorId) {
	$oldProduct->setColorId($oldProductColorId);
}

ViewOld::setThumbnail($oldProduct->clientFullImage)
	->setTitle($oldProduct->name);

// get item seller
$seller = $product->seller;

// redirect if not public
$owner = $oldProduct->isUserOwner;
$vip = $owner || is_admin() ? true : false;

$wip = !$seller->status;
if ($wip && !$vip) redirect_old('/shop');

// select following status
$foll = DBOld::exists('followers WHERE user_id = ? AND follower_id = ?', [auth()->id(), $oldProduct->seller->id]);

// select fan pics
$res_fan = DBOld::select('* FROM home_pics WHERE design_id = ? ORDER BY id DESC LIMIT ?', [$oldProduct->design->id, 5]);
$fanN = $res_fan->num_rows;

/*
* Related products
*/
$relatedGarments = rows_to_objects(
	DBOld::select(
		"items.id FROM items
		INNER JOIN items_types ON items.type_id = items_types.id
		WHERE design_id = ? AND type_id <> ? AND category='c' AND items.status = 1 AND items.deleted = 0
		ORDER BY type_id ASC, items.sort ASC, items_types.sort ASC, items_types.id ASC",
		[$oldProduct->design->id, $oldProduct->type->id]
	),
	OldProduct::class
);

$relatedAccessories = rows_to_objects(DBOld::select("id FROM items WHERE design_id = ? AND type_id <> ? AND type_id IN (SELECT id FROM items_types WHERE category='m') AND status = 1 ORDER BY type_id ASC", [$oldProduct->design->id, $oldProduct->type->id]), OldProduct::class);

$relatedProdutsVariables = [$seller->id, $oldProduct->design->id, $oldProduct->type->id, 8];
$relatedProdutsVariables = array_merge($relatedProdutsVariables, $relatedProdutsVariables);

$relatedGarmentCount = count($relatedGarments);
$relatedAccessoryCount = count($relatedAccessories);

/**
 * Related products
 */
$relatedProducts = Product::where('user_id', $seller->id)
	->where(function ($query) use ($product) {
		$query->where('design_id', '<>', $product->design_id)
			->orWhere('design_id', 0);
	})
	->where('status', '>', 0)
	->where('type_id', $product->type_id)
	->where('deleted', 0)
	->orderBy('sales', 'desc')
	->orderBy('id', 'desc')
	->limit(8)
	->get();

if ($relatedProducts->count() < 8) {
	$moreRelatedProducts = Product::where('user_id', $seller->id)
		->where(function ($query) use ($product) {
			$query->where('design_id', '<>', $product->design_id)
				->orWhere('design_id', 0);
		})
		->where('status', '>', 0)
		->where('type_id', '<>', $product->type_id)
		->where('deleted', 0)
		->orderBy('sales', 'desc')
		->orderBy('id', 'desc')
		->limit(8 - $relatedProducts->count())
		->get();

	$relatedProducts = $relatedProducts->merge($moreRelatedProducts);
}

// warnings
$warning = warning_banner();

// add order
if (isset($_POST['submit'])) {
	$config['product_id'] = $id;
	$config['quantity'] = $_POST['quantity'];

	if (isset($_POST['cbx-color'])) foreach ($_POST['cbx-color'] as $config['colorId']);
	if (isset($_POST['cbx-size'])) foreach ($_POST['cbx-size'] as $config['size_id']);
	if (isset($_POST['cbx-gender'])) foreach ($_POST['cbx-gender'] as $config['genderId']);

	if (isset($_POST['logo-id'])) {
		$config['logo_id'] = $_POST['logo-id'];
	}

	if (isset($_POST['cbx-pos'])) {
		foreach ($_POST['cbx-pos'] as $config['position_id']);
	} else {
		$config['position_id'] = $oldProduct->availablePositionIds[0];
	}

	Order::add($config);

	redirect_old('/cart');
}
