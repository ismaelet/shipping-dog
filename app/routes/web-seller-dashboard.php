<?php

use App\Http\Controllers\Ecommerce\DesignController;
use App\Http\Controllers\Ecommerce\PaymentRequestController;
use App\Http\Controllers\Ecommerce\SaleController;
use App\Http\Controllers\SellerDashboard\PortfolioController;
use App\Http\Controllers\SellerDashboard\PricingController;
use App\Http\Controllers\SellerDashboard\SellerDashboardController;
use App\Http\Middleware\Seller;
use Illuminate\Support\Facades\Route;

// Seller dashboard
Route::prefix('/seller')->middleware(Seller::class)->name('seller-dashboard')->group(function () {

	// Home
	Route::get('', [SellerDashboardController::class, 'index'])->name('');
	Route::post('{seller}/settings', [SellerDashboardController::class, 'update'])->name('.settings');
	Route::put('{seller}/contacts', [SellerDashboardController::class, 'updateContacts'])->name('.contacts');
	Route::post('{seller}/icon', [SellerDashboardController::class, 'uploadSellerIcon'])->name('.icon');
	Route::post('{seller}/cover', [SellerDashboardController::class, 'uploadSellerCover'])->name('.cover');

	// Portfolio
	Route::get('/portfolio', [PortfolioController::class, 'index'])->name('.portfolio');
	Route::get('/portfolio/add', [PortfolioController::class, 'add'])->name('.portfolio.add');

	// Sales
	Route::get('/sales', [SaleController::class, 'index'])->name('.sales');

	// Pricing
	Route::get('/sales/prices', [PricingController::class, 'index'])->name('.prices');
	Route::post('/sales/prices', [PricingController::class, 'update'])->name('.prices');

	// Payments
	Route::get('/payment', [PaymentRequestController::class, 'index'])->name('.payment');
	Route::get('/payment/edit', [PaymentRequestController::class, 'edit'])->name('.payment.edit');
	Route::post('/payment/update', [PaymentRequestController::class, 'update'])->name('.payment.update');
	Route::post('/payment/download', [PaymentRequestController::class, 'download'])->name('.payment.download');

	// Customer photos
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
