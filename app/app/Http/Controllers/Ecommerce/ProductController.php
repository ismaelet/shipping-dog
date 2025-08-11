<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use App\Models\Warning;
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

		$sameDesignProducts = Product::whereHas('type', function ($query) {
			$query->doesntHave('main_type');
		})
			->where('design_id', $product->design_id)
			->where('type_id', '<>', $product->type_id)
			->where('products.status', 1)
			->where('products.deleted', 0)
			->with('design', 'design_with_trashed', 'color', 'position', 'type')
			->get();

		$relatedGarments = $sameDesignProducts->where('type.category', 'c')->sortBy('type.sort');
		$relatedAccessories = $sameDesignProducts->where('type.category', 'm')->sortBy('type.sort');

		$productColors = $product->colors();

		$warning = Warning::currentWarning();

		return view('ecommerce.product', [
			'product' => $product,
			'locationTag' => 'shop',
			'title' => $title,
			'thumbnail' => $thumbnail,
			'productColors' => $productColors,
			'relatedGarments' => $relatedGarments,
			'relatedAccessories' => $relatedAccessories,
			'warning' => $warning,
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
