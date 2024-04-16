<?php

namespace App\Http\Controllers\Interactions;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Seller\Follower;

class FollowerController extends Controller {

	/**
	 * Follow a seller
	 */
	public static function follow(): int|false {
		$seller_id = static::body('seller_id');
		$userId = static::body('userId') ?? auth()->id();
		$requestData = [
			'user_id' => $userId,
			'follower_id' => $seller_id,
		];

		if (!Follower::where($requestData)->count()) {

			$follower = Follower::create([
				'user_id' => $userId,
				'follower_id' => $seller_id,
			]);

			return $follower->id;
		}

		return false;
	}

	/**
	 * Unfollow a seller
	 */
	public static function unfollow(): bool {
		$seller_id = static::body('seller_id');
		$userId = static::body('userId') ?? auth()->id();
		$requestData = [
			'user_id' => $userId,
			'follower_id' => $seller_id,
		];

		return Follower::where($requestData)->delete();
	}
}
