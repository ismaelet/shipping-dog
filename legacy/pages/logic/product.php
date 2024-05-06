<?php

use App\Helpers\ArrayHelper;
use App\Models\Ecommerce\Product;
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
$product = $id ? Product::with('design', 'seller', 'color', 'type', 'position', 'specific_price', 'seller_price', 'base_price')->find($id) : null;
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
	$product->color_id = $productColorId;
}

if ($productPositionId) {
	$product->position_id = $productPositionId;
}

/*
* Related products
*/
$sameDesignProducts = Product::join('product_types', 'products.type_id', '=', 'product_types.id')
	->where('design_id', $product->design_id)
	->where('type_id', '<>', $product->type_id)
	->where('products.status', 1)
	->where('products.deleted', 0)
	->orderBy('product_types.sort')
	->with('design', 'color', 'position', 'type')
	->get(['products.*', 'product_types.category']);

$relatedGarments = $sameDesignProducts->where('category', 'c');
$relatedAccessories = $sameDesignProducts->where('category', 'm');

$relatedGarmentCount = $relatedGarments->count();
$relatedAccessoryCount = $relatedAccessories->count();

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
	->with('design', 'type', 'color', 'position', 'type')
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
		->with('design', 'type', 'color', 'position', 'type')
		->orderBy('sales', 'desc')
		->orderBy('id', 'desc')
		->limit(8 - $relatedProducts->count())
		->get();

	$relatedProducts = $relatedProducts->merge($moreRelatedProducts);
}

/**
 * * Set the page meta data
 */
LegacyView::setThumbnail($product->image(1024))
	->setTitle($product->name);
