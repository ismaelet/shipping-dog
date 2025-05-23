<?php

use App\Http\Controllers\Ecommerce\PaymentRequestController;
use App\Http\Controllers\Ecommerce\SaleController;
use App\Http\Controllers\SellerDashboard\SellerDashboardController;
use App\Http\Middleware\Seller;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->middleware(Seller::class)->name('seller.')->group(function () {
	Route::get('/payment', [PaymentRequestController::class, 'index'])->name('payment');
	Route::get('/payment/edit', [PaymentRequestController::class, 'edit'])->name('payment.edit');
	Route::post('/payment/update', [PaymentRequestController::class, 'update'])->name('payment.update');
	Route::post('/payment/download', [PaymentRequestController::class, 'download'])->name('payment.download');
});

Route::prefix('/seller-dashboard')->middleware(Seller::class)->name('seller-dashboard')->group(function () {
	Route::post('{seller}/icon', [SellerDashboardController::class, 'uploadSellerIcon'])->name('.icon');
	Route::post('{seller}/cover', [SellerDashboardController::class, 'uploadSellerCover'])->name('.cover');

	Route::get('/sales', [SaleController::class, 'index'])->name('.sales');
});
