<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller {

	/**
	 * * Action: show
	 */
	public function show(Product $product, Request $request): View|RedirectResponse {
		if (
			!$product?->id ||
			!$product?->seller ||
			$product->trashed() ||
			($product->status == 0 && !$product->userIsOwner() && !is_admin())
		) {
			return redirect()->route('shop');
		}

		if ($request->color_id) {
			$product->color_id = $request->color_id;
		}

		if ($request->position_id) {
			$product->position_id = $request->position_id;
		}

		$title = $product->name;
		$thumbnail = $product->image(1024);

		$seller = $product->seller;

		$sameDesignProducts = Product::join('product_types', 'products.type_id', '=', 'product_types.id')
			->where('design_id', $product->design_id)
			->where('type_id', '<>', $product->type_id)
			->where('products.status', 1)
			->where('products.deleted', 0)
			->orderBy('product_types.sort')
			->with('design', 'design_with_trashed', 'color', 'position', 'type')
			->get(['products.*', 'product_types.category']);

		$relatedGarments = $sameDesignProducts->where('category', 'c')->sortBy('product_types.sort');
		$relatedAccessories = $sameDesignProducts->where('category', 'm')->sortBy('product_types.sort');

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

		$productColors = $product->colors();

		return view('ecommerce.product', [
			'product' => $product,
			'locationTag' => 'shop',
			'title' => $title,
			'thumbnail' => $thumbnail,
			'productColors' => $productColors,
			'relatedGarments' => $relatedGarments,
			'relatedAccessories' => $relatedAccessories,
			'relatedProducts' => $relatedProducts,
		]);
	}

	/**
	 * * Custom action: Change the status
	 */
	public function changeStatus(Product $product, Request $request): RedirectResponse {
		$request->validate([
			'status' => 'required|in:0,1',
		]);

		$product->update([
			'status' => $request->status,
		]);

		return redirect()->back();
	}
}
