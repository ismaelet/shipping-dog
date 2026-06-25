<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design\Logo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoController extends Controller {

	/**
	 * Get the specific logo data
	 */
	public function show(Request $request, int $id): JsonResponse {
		$logo = Logo::findOrFail($id);

		return response()->json([
			'image' => $logo->image()
		]);
	}
}
