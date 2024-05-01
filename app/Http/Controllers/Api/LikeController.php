<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller {

	/**
	 * Store a new like
	 */
	public function store(Request $request): JsonResponse {
		$exists = Like::where([
			'user_id' => $request->user_id,
			'design_id' => $request->design_id,
		])->exists();

		if (!$exists) {
			Like::create([
				'user_id' => $request->user_id,
				'design_id' => $request->design_id,
			]);

			return response()->json([
				'success' => true
			]);
		}

		return response()->json([
			'success' => false
		]);
	}

	/**
	 * Destroy the specified like
	 */
	public function destroy(Request $request, int $id): JsonResponse {
		$like = Like::findOrFail($id);
		$userId = $like->user_id;
		$designId = $like->design_id;

		Like::where([
			'user_id' => $userId,
			'design_id' => $designId,
		])->delete();

		return response()->json([
			'success' => true
		]);
	}
}
