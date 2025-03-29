<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PreviewHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product\ProductMockup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductMockupController extends Controller {

	/**
	 * * Action: Store
	 */
	public function store(Request $request): JsonResponse {
		$request->validate([
			'product_id' => ['required', 'integer', 'exists:products,id'],
			'position_id' => ['required', 'integer', 'exists:product_positions,id'],
			'size' => 'required|string',
			'pos_x' => 'required|integer',
			'pos_y' => 'required|integer',
		]);

		$exists = ProductMockup::where('product_id', $request->product_id)
			->where('position_id', $request->position_id)
			->exists();

		if ($exists) {
			return response()->json([
				'error' => 'This mockup already exists',
			], 400);
		}

		$mockup = ProductMockup::create($request->only([
			'product_id',
			'position_id',
			'size',
			'pos_x',
			'pos_y',
		]));

		$this->regeneratePreviews($mockup);

		return response()->json($mockup);
	}

	/**
	 * * Action: Update
	 */
	public function update(ProductMockup $mockup, Request $request): JsonResponse {
		$request->validate([
			'size' => 'string',
			'pos_x' => 'integer',
			'pos_y' => 'integer',
		]);

		$mockup->update($request->only([
			'size',
			'pos_x',
			'pos_y',
		]));

		$this->regeneratePreviews($mockup);

		return response()->json($mockup);
	}

	/**
	 * ~ Helper: Regenerate Previews
	 */
	private function regeneratePreviews(ProductMockup $mockup): void {
		PreviewHelper::deleteProductPreview($mockup->product);
	}
}
