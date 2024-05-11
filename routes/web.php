<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\Ecommerce\DesignController;
use App\Http\Controllers\Ecommerce\PaymentRequestController;
use App\Http\Controllers\Ecommerce\SaleController;
use App\Http\Controllers\EmergencyEmail;
use App\Http\Controllers\LegacyController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Seller;
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
 * * Sellers
 */
// Seller shop
Route::any('/artist', LegacyController::class)->name('seller');

// Seller dashboard
Route::prefix('/manage')->middleware(Seller::class)->name('old-seller-dashboard')->group(function () {
	Route::any('', LegacyController::class);
	Route::any('/portfolio', LegacyController::class)->name('.portfolio');
	Route::any('/portfolio/add', LegacyController::class)->name('.portfolio.add');
	Route::any('/sales/list', LegacyController::class)->name('.sales');
	Route::any('/sales/prices', LegacyController::class)->name('.prices');
});

Route::prefix('seller')->middleware(Seller::class)->name('seller.')->group(function () {
	Route::get('/payment', [PaymentRequestController::class, 'index'])->name('payment');
	Route::get('/payment/edit', [PaymentRequestController::class, 'edit'])->name('payment.edit');
	Route::post('/payment/update', [PaymentRequestController::class, 'update'])->name('payment.update');
	Route::post('/payment/download', [PaymentRequestController::class, 'download'])->name('payment.download');
});

// !!! Working on
Route::prefix('/seller-dashboard')->middleware(Seller::class)->name('seller-dashboard')->group(function () {
	Route::get('/sales', [SaleController::class, 'index'])->name('.sales');
});

/**
 * * Designs
 */
// Create a design
Route::resource('designs', DesignController::class)->only(['index', 'store', 'edit', 'destroy']);
Route::post('/designs/{design}/upload-image', [DesignController::class, 'uploadImage'])->name('designs.upload-image');
Route::post('/designs/{design}/update-tags', [DesignController::class, 'updateTags'])->name('designs.update-tags');
Route::post('/designs/{design}/update-background-color', [DesignController::class, 'updateBackgroundColor'])->name('designs.update-background-color');
Route::post('/designs/{design}/update-default-color', [DesignController::class, 'updateDefaultColor'])->name('designs.update-default-color');

/**
 * * Downloads
 */
Route::get('/download/{filename}', [DownloadController::class, 'download'])->name('download')->where('filename', '.*');

/**
 * * Misc
 */
Route::get('/emergency-email', [EmergencyEmail::class, 'sendEmail'])->middleware(Admin::class);

/**
 * * Legacy routes
 */
Route::any('/', LegacyController::class)->name('home');
Route::any('/profile', LegacyController::class)->name('user-dashboard');
Route::any('/order/status', LegacyController::class)->name('order_items.status');
Route::any('/refund', LegacyController::class)->name('refund');
Route::any('/contacts', LegacyController::class)->name('contacts');
Route::any('/infos/faq', LegacyController::class)->name('infos.faq');
Route::any('{path}', LegacyController::class)->where('path', '.*');
