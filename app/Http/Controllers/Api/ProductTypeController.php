<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductTypeController extends Controller {

	/**
	 * * Action: Show
	 */
	public function getImage(ProductType $type, Request $request): JsonResponse {
		$request->validate([
			'color_id' => ['integer', 'exists:product_colors,id'],
			'position_id' => ['integer', 'exists:product_positions,id'],
			'suffix' => ['nullable', 'string'],
		]);

		return response()->json([
			'id' => $type->id,
			'color_id' => $request->color_id,
			'position_id' => $request->position_id,
			'suffix' => $request->suffix,
			'image' => public_asset($type->image($request->color_id, $request->position_id, $request->suffix ?: '')),
		]);
	}
}
