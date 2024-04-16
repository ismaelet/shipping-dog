<?php

namespace App\Http\Controllers\Interactions;

use App\Http\Controllers\Controller;
use App\Models\User\Like;

class LikeController extends Controller {

	/**
	 * Like a design
	 */
	public static function like(): int|false {
		$design_id = static::body('design_id');
		$userId = static::body('userId') ?? auth()->id();
		$requestData = [
			'user_id' => $userId,
			'design_id' => $design_id,
		];

		if (!Like::where($requestData)->count()) {
			$like = Like::create([
				'user_id' => $userId,
				'design_id' => $design_id,
			]);

			return $like->id;
		}

		return false;
	}

	/**
	 * Unfollow a seller
	 */
	public static function unlike(): bool {
		$design_id = static::body('design_id');
		$userId = static::body('userId') ?? auth()->id();
		$requestData = [
			'user_id' => $userId,
			'design_id' => $design_id,
		];

		return Like::where($requestData)->delete();
	}
}
