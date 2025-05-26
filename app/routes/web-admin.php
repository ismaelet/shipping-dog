<?php

use App\Http\Controllers\Admin\OrdersLogController;
use App\Http\Controllers\Admin\ProductsLogController;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->name('admin.')->middleware(Admin::class)->group(function () {

	// Logs
	Route::post('/orders-log/clean', [OrdersLogController::class, 'clean'])->name('orders-log.clean');
	Route::post('/orders-log/update', [OrdersLogController::class, 'updateStartFrom'])->name('orders-log.update-start-from');

	Route::post('/products-log/clean', [ProductsLogController::class, 'clean'])->name('products-log.clean');
	Route::post('/products-log/update', [ProductsLogController::class, 'updateStartFrom'])->name('products-log.update-start-from');
});
