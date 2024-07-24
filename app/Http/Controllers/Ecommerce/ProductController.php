<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller {

	/**
	 * * Action: show
	 */
	public function show(Product $product, Request $request) {
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

		$request->merge([
			'product' => $product,
		]);

		\Legacy\System\Classes\LegacyView::setPage('product')
			->setLocation('shop')
			->setThumbnail($product->image(1024))
			->setTitle($product->name)
			->show();
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
