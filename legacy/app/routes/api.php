<?php

use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Interactions\FollowerController;
use App\Http\Controllers\Interactions\LikeController;

/**
 * Carts
 */
RouteOld::add('/api/carts', function () {
	echo json_encode(CartController::get());
});


/**
 * Likes
 */
RouteOld::add('/api/like', function () {
	echo json_encode(LikeController::like());
});

RouteOld::add('/api/unlike', function () {
	echo json_encode(LikeController::unlike());
});

/**
 * Followers
 */
RouteOld::add('/api/follow', function () {
	echo json_encode(FollowerController::follow());
});

RouteOld::add('/api/unfollow', function () {
	echo json_encode(FollowerController::unfollow());
});
