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
	public function show(Product $product): RedirectResponse {
		redirect_old('/products/' . $product->slug());
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
