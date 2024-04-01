<?php

use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Controllers\Interactions\FollowerController;
use App\Http\Controllers\Interactions\LikeController;

/**
 * Carts
 */
Route::add('/api/carts', function () {
	echo json_encode(CartController::get());
});


/**
 * Likes
 */
Route::add('/api/like', function () {
	echo json_encode(LikeController::like());
});

Route::add('/api/unlike', function () {
	echo json_encode(LikeController::unlike());
});

/**
 * Followers
 */
Route::add('/api/follow', function () {
	echo json_encode(FollowerController::follow());
});

Route::add('/api/unfollow', function () {
	echo json_encode(FollowerController::unfollow());
});
