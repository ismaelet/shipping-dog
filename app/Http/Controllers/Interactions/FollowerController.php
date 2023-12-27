<?php

namespace App\Http\Controllers\Interactions;

use App\Http\Controllers\Controller;
use App\Models\Seller\Follower;

class FollowerController extends Controller {

	/**
	 * Follow a seller
	 */
	public static function follow(): int|false {
		$sellerId = static::body('sellerId');
		$userId = static::body('userId') ?? userId();
		$requestData = [
			'userID' => $userId,
			'followID' => $sellerId,
		];

		if (!Follower::where($requestData)->count()) {

			$follower = Follower::create([
				'userID' => $userId,
				'followID' => $sellerId,
			]);

			return $follower->ID;
		}

		return false;
	}

	/**
	 * Unfollow a seller
	 */
	public static function unfollow(): bool {
		$sellerId = static::body('sellerId');
		$userId = static::body('userId') ?? userId();
		$requestData = [
			'userID' => $userId,
			'followID' => $sellerId,
		];

		return Follower::where($requestData)->delete();
	}
}
