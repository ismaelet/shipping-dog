<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller {

	/**
	 * * Action: Show
	 */
	public function show(Product $product, Request $request): JsonResponse {
		$request->validate([
			'image_size' => ['nullable', 'integer', 'in:480,1024'],
			'color_id' => ['nullable', 'integer', 'exists:product_colors,id'],
			'logo_id' => ['nullable', 'integer', 'exists:logos,id'],
			'position_id' => ['nullable', 'integer', 'exists:product_positions,id'],
		]);

		// Set the color
		if ($request->has('color_id')) {
			$product->color_id = $request->color_id;
		}

		// Set the logo
		if ($request->has('logo_id')) {
			$product->logo_id = $request->logo_id;
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

	/**
	 * * Action: Update
	 */
	public function update(Product $product, Request $request): JsonResponse {
		$changes = [];

		$request->validate([
			'colors' => ['nullable', 'array'],
			'color_id' => ['nullable', 'integer', 'exists:product_colors,id'],
			'positions' => ['nullable', 'array'],
		]);

		if ($request->color_id) {
			$changes['color_id'] = $request->color_id;
		}

		if ($request->colors) {
			$changes['colors'] = implode(',', $request->colors);
		}

		if ($request->positions) {
			$changes['positions'] = implode(',', $request->positions);
		}

		$product->update($changes);

		return response()->json($product);
	}
}
