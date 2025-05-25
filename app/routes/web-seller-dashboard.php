<?php

use App\Http\Controllers\Ecommerce\PaymentRequestController;
use App\Http\Controllers\Ecommerce\SaleController;
use App\Http\Controllers\SellerDashboard\PortfolioController;
use App\Http\Controllers\SellerDashboard\SellerDashboardController;
use App\Http\Middleware\Seller;
use Illuminate\Support\Facades\Route;

// Seller dashboard
Route::prefix('/seller')->middleware(Seller::class)->name('seller-dashboard')->group(function () {
	// Home
	Route::any('', [SellerDashboardController::class, 'index'])->name('');
	Route::post('{seller}/icon', [SellerDashboardController::class, 'uploadSellerIcon'])->name('.icon');
	Route::post('{seller}/cover', [SellerDashboardController::class, 'uploadSellerCover'])->name('.cover');

	// Portfolio
	Route::any('/portfolio', [PortfolioController::class, 'index'])->name('.portfolio');
	Route::any('/portfolio/add', [PortfolioController::class, 'add'])->name('.portfolio.add');
	Route::any('/sales/prices', [PortfolioController::class, 'prices'])->name('.prices');

	// Sales
	Route::get('/sales', [SaleController::class, 'index'])->name('.sales');

	// Payments
	Route::get('/payment', [PaymentRequestController::class, 'index'])->name('.payment');
	Route::get('/payment/edit', [PaymentRequestController::class, 'edit'])->name('.payment.edit');
	Route::post('/payment/update', [PaymentRequestController::class, 'update'])->name('.payment.update');
	Route::post('/payment/download', [PaymentRequestController::class, 'download'])->name('.payment.download');

	// Customer photos
});
