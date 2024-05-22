<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Seller\Follower;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowerController extends Controller {

	/**
	 * Store a new follower
	 */
	public function store(Request $request): JsonResponse {
		$exists = Follower::where([
			'follower_id' => $request->follower_id,
			'user_id' => $request->user_id,
		])->exists();

		if (!$exists) {
			$follower = Follower::create([
				'follower_id' => $request->follower_id,
				'user_id' => $request->user_id,
			]);

			return response()->json([
				'success' => true,
				'follower' => $follower->toArray(),
			]);
		}

		return response()->json([
			'success' => false
		]);
	}

	/**
	 * Destroy the specified follower
	 */
	public function destroy(Request $request, int $id): JsonResponse {
		$follower = Follower::findOrFail($id);
		$userId = $follower->user_id;
		$followerId = $follower->follower_id;

		Follower::where([
			'follower_id' => $followerId,
			'user_id' => $userId,
		])->delete();

		return response()->json([
			'success' => true
		]);
	}
}
