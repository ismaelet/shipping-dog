<?php

namespace App\Http\Controllers\Interactions;

use App\Http\Controllers\Controller;
use App\Models\User\Like;

class LikeController extends Controller {

	/**
	 * Like a design
	 */
	public static function like(): int|false {
		$designId = static::body('designId');
		$userId = static::body('userId') ?? auth()->id();
		$requestData = [
			'userID' => $userId,
			'desID' => $designId,
		];

		if (!Like::where($requestData)->count()) {
			$like = Like::create([
				'userID' => $userId,
				'desID' => $designId,
			]);

			return $like->ID;
		}

		return false;
	}

	/**
	 * Unfollow a seller
	 */
	public static function unlike(): bool {
		$designId = static::body('designId');
		$userId = static::body('userId') ?? auth()->id();
		$requestData = [
			'userID' => $userId,
			'desID' => $designId,
		];

		return Like::where($requestData)->delete();
	}
}
