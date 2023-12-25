<?php

use App\Http\Controllers\Interactions\FollowerController;

/**
 * Followers
 */
Route::add('/api/follow', function () {
	echo json_encode(FollowerController::follow());
});

Route::add('/api/unfollow', function () {
	echo json_encode(FollowerController::unfollow());
});
