<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller {

	/**
	 * Get the specific product data
	 */
	public function show(Request $request, int $id): JsonResponse {
		$product = Product::findOrFail($id);

		// Set the color
		if ($request->has('color_id')) {
			$product->color_id = $request->color_id;
		}

		// Set the logo
		if ($request->has('logo_id')) {
			$product->logoId($request->logo_id);
		}

		return response()->json([
			'id' => $product->id,
			'name' => $product->name,
			'design_id' => $product->design_id,
			'user_id' => $product->user_id,
			'type_id' => $product->type_id,
			'color_id' => $product->color_id,
			'colors' => $product->colors,
			'status' => $product->status,
			'positions' => $product->positions,
			'priceString' => $product->priceString(),
			'fullPriceString' => $product->fullPriceString(),
			'customerPriceString' => $product->fullPriceString(),
			'image' => $product->image($request->image_size, $request->color_id, $request->position_id),
		]);
	}
}
