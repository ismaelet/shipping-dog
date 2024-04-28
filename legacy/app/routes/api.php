<?php

use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Interactions\FollowerController;
use App\Http\Controllers\Interactions\LikeController;

/**
 * Carts
 */
\Legacy\System\Classes\LegacyRoute::add('/api/carts', function () {
	echo json_encode(CartController::get());
});


/**
 * Likes
 */
\Legacy\System\Classes\LegacyRoute::add('/api/like', function () {
	echo json_encode(LikeController::like());
});

\Legacy\System\Classes\LegacyRoute::add('/api/unlike', function () {
	echo json_encode(LikeController::unlike());
});

/**
 * Followers
 */
\Legacy\System\Classes\LegacyRoute::add('/api/follow', function () {
	echo json_encode(FollowerController::follow());
});

\Legacy\System\Classes\LegacyRoute::add('/api/unfollow', function () {
	echo json_encode(FollowerController::unfollow());
});
