<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DesignController extends Controller {

	/**
	 * Update the specific design data
	 */
	public function update(Request $request, int $id): JsonResponse {
		$design = Design::findOrFail($id);

		$request->validate([
			'name' => ['required', 'string', 'max:255'],
		]);

		$design->update($request->only('name'));

		return response()->json([
			'design' => $design
		]);
	}
}
