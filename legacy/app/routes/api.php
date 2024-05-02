<?php

use App\Http\Controllers\Ecommerce\OrderItemController;
use App\Http\Controllers\Interactions\FollowerController;
use App\Http\Controllers\Interactions\LikeController;

/**
 * orders
 */
\Legacy\System\Classes\LegacyRoute::add('/api/orders', function () {
	echo json_encode(OrderController::get());
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
