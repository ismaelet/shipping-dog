<?php

use App\Models\Ecommerce\Product;

/**
 * * Fetch the record
 */
$product = request()->product;
$seller = $product->seller;

/*
* Related products
*/
$sameDesignProducts = Product::join('product_types', 'products.type_id', '=', 'product_types.id')
	->where('design_id', $product->design_id)
	->where('type_id', '<>', $product->type_id)
	->where('products.status', 1)
	->where('products.deleted', 0)
	->orderBy('product_types.sort')
	->with('design', 'design_with_trashed', 'color', 'position', 'type')
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
	->with('design', 'design_with_trashed', 'type', 'color', 'position', 'type')
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
		->with('design', 'design_with_trashed', 'type', 'color', 'position', 'type')
		->orderBy('sales', 'desc')
		->orderBy('id', 'desc')
		->limit(8 - $relatedProducts->count())
		->get();

	$relatedProducts = $relatedProducts->merge($moreRelatedProducts);
}

/**
 * * Product data
 */
$productColors = $product->colors();
$productColorCount = $productColors->count();
