<?php

use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\LogoController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Products
 */
Route::resource('/products', ProductController::class)->only(['show']);

/**
 * Logos
 */
Route::resource('/logos', LogoController::class)->only(['show']);

/**
 * Likes
 */
Route::resource('/likes', LikeController::class)->only(['store', 'destroy']);

/**
 * Followers
 */
Route::resource('/followers', FollowerController::class)->only(['store', 'destroy']);

// RouteOld::add('/api/unlike', function () {
// 	echo json_encode(LikeController::unlike());
// });