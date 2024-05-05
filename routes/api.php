<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DesignController;
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
 * Designs
 */
Route::resource('/designs', DesignController::class)->only(['update']);

/**
 * Products
 */
Route::resource('/products', ProductController::class)->only(['show']);

/**
 * Logos
 */
Route::resource('/logos', LogoController::class)->only(['show']);

/**
 * orders
 */
Route::resource('/orders', OrderController::class)->only(['index', 'show', 'update']);

/**
 * Likes
 */
Route::resource('/likes', LikeController::class)->only(['store', 'destroy']);

/**
 * Followers
 */
Route::resource('/followers', FollowerController::class)->only(['store', 'destroy']);
