<?php

use App\Http\Controllers\LegacyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Auth routes
 */
include_once __DIR__ . '/authentication.php';

/**
 * Admin routes
 */
include_once __DIR__ . '/admin.php';

/**
 * Ecommerce routes
 */
include_once __DIR__ . '/web-ecommerce.php';

/**
 * Legacy routes
 */
Route::any('{path}', LegacyController::class)->where('path', '.*');
Route::any('/', LegacyController::class)->name('home');
