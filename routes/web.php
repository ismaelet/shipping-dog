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
Route::any('/', LegacyController::class)->name('home');
Route::any('/profile', LegacyController::class)->name('user-dashboard');
Route::any('/artist', LegacyController::class)->name('seller');
Route::any('/order/status', LegacyController::class)->name('order_items.status');
Route::any('/refund', LegacyController::class)->name('refund');
Route::any('/contacts', LegacyController::class)->name('contacts');
Route::any('/infos/faq', LegacyController::class)->name('infos.faq');
Route::any('{path}', LegacyController::class)->where('path', '.*');

Route::prefix('/manage')->middleware('auth')->name('seller-dashboard')->group(function () {
	Route::any('', LegacyController::class);
	Route::any('/portfolio', LegacyController::class)->name('.portfolio');
	Route::any('/portfolio/add', LegacyController::class)->name('.portfolio.add');
	Route::any('/sales/list', LegacyController::class)->name('.sales');
	Route::any('/sales/prices', LegacyController::class)->name('.prices');
});
