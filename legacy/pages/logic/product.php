<?php

use App\Helpers\ArrayHelper;
use App\Models\Ecommerce\Product;
use Legacy\Classes\Ecommerce\Order as LegacyOrder;
use Legacy\Classes\Ecommerce\Product as LegacyProduct;
use Legacy\System\Classes\LegacyDB;
use Legacy\System\Classes\LegacyView;

/**
 * ? Legacy support
 */
$id = $_GET['id'] ?? null;

/**
 * * Request data
 */
$slug = ArrayHelper::explodeLast('/', request()->url())[1] ?? null;
$slugId = explode('-', $slug)[0] ?? null;
$id = $id ?: (int) $slugId;
$productPositionId = $_GET['position_id'] ?? null;
$productColorId = $_GET['color_id'] ?? null;

/**
 * * Fetch the record
 */
$product = $id ? Product::find($id) : null;
$legacyProduct = $id ? new LegacyProduct($id) : null;
$seller = $product?->seller;

/**
 * * Redirect to the shop when the product should not be displayed
 */
// Redirect to the shop it the product or seller was not found
if (!$product || !$seller || !$product->status || $product->deleted) {
	redirect_old('/shop');
}

// Redirect to the shop if the seller is not active and the user is not the seller or an admin
if (!$seller->status && !$product->isUserOwner && !is_admin()) {
	redirect_old('/shop');
}

/**
 * * Set the product position and color
 */
if ($productColorId) {
	$legacyProduct->setColorId($productColorId);
}

if ($productPositionId) {
	$legacyProduct->setPositionId($productPositionId);
}

/*
* Related products
*/
$relatedGarments = rows_to_objects(
	LegacyDB::select(
		"items.id FROM items
		INNER JOIN product_types ON items.type_id = product_types.id
		WHERE design_id = ? AND type_id <> ? AND category='c' AND items.status = 1 AND items.deleted = 0
		ORDER BY type_id ASC, items.sort ASC, product_types.sort ASC, product_types.id ASC",
		[$legacyProduct->design_id, $legacyProduct->type_id]
	),
	LegacyProduct::class
);

$relatedAccessories = rows_to_objects(LegacyDB::select("id FROM items WHERE design_id = ? AND type_id <> ? AND type_id IN (SELECT id FROM product_types WHERE category='m') AND status = 1 AND deleted = 0 ORDER BY type_id ASC", [$legacyProduct->design_id, $legacyProduct->type_id]), LegacyProduct::class);

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

/**
 * * Set the page meta data
 */
LegacyView::setThumbnail($legacyProduct->clientFullImage)
	->setTitle($legacyProduct->name);

/**
 * * Add an order
 */
if (isset($_POST['submit'])) {
	$config['product_id'] = $id;
	$config['quantity'] = $_POST['quantity'];

	if (isset($_POST['cbx-color'])) foreach ($_POST['cbx-color'] as $config['color_id']);
	if (isset($_POST['cbx-size'])) foreach ($_POST['cbx-size'] as $config['size_id']);
	if (isset($_POST['cbx-gender'])) foreach ($_POST['cbx-gender'] as $config['genderId']);

	if (isset($_POST['logo-id'])) {
		$config['logo_id'] = $_POST['logo-id'];
	}

	if (isset($_POST['cbx-pos'])) {
		foreach ($_POST['cbx-pos'] as $config['position_id']);
	} else {
		$config['position_id'] = $legacyProduct->availablePositionIds[0];
	}

	LegacyOrder::add($config);

	redirect_old('/cart');
}
