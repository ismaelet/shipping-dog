<?php

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
 * App routes
 */

Route::get('/infos/sale-terms', function () {
	return view('/infos/sale-terms')->with('title', 'Condizioni di vendita');
})->name('infos.sale-terms');

/**
 * Legacy
 */
Route::get('/shop', function () {
	return redirect('/public/legacy' . $_SERVER['REQUEST_URI']);
})->name('shop');

Route::get('/{any}', function ($any) {
	return redirect('/public/legacy/' . $any);
})->where('any', '.*');

Route::get('/', function () {
	return redirect('/public/legacy');
})->name('home');

Route::get('/cart', function () {
	return redirect('/public/legacy/cart');
})->name('cart');

Route::get('/login', function () {
	return redirect('/public/legacy/login');
})->name('login');

Route::get('/artist', function () {
	return redirect('/public/legacy/artist');
})->name('artist');

Route::get('/profile', function () {
	return redirect('/public/legacy/profile');
})->name('profile');

Route::get('/wishlist', function () {
	return redirect('/public/legacy/wishlist');
})->name('wishlist');

Route::get('/sell', function () {
	return redirect('/public/legacy/sell');
})->name('sell');

Route::get('/infos/faq', function () {
	return redirect('/public/legacy/infos/faq');
})->name('infos.faq');

Route::get('/infos/contract', function () {
	return redirect('/public/legacy/infos/contract');
})->name('infos.contract');

Route::get('/infos/privacy', function () {
	return redirect('/public/legacy/infos/privacy');
})->name('infos.privacy');

Route::get('/admin', function () {
	return redirect('/public/legacy/admin');
})->name('admin');

Route::get('/contacts', function () {
	return redirect('/public/legacy/contacts');
})->name('contacts');

Route::get('/refund', function () {
	return redirect('/public/legacy/refund');
})->name('refund');

Route::get('/order/status', function () {
	return redirect('/public/legacy/order/status');
})->name('order/status');

Route::get('/manage', function () {
	return redirect('/public/legacy/manage');
})->name('manage');

Route::get('/manage/portfolio', function () {
	return redirect('/public/legacy/manage/portfolio');
})->name('manage/portfolio');

Route::get('/manage/portfolio/add', function () {
	return redirect('/public/legacy/manage/portfolio/add');
})->name('manage/portfolio/add');

Route::get('/manage/sales/list', function () {
	return redirect('/public/legacy/manage/sales/list');
})->name('manage/sales/list');

Route::get('/manage/sales/prices', function () {
	return redirect('/public/legacy/manage/sales/prices');
})->name('manage/sales/prices');

Route::get('/search', function () {
	return redirect('/public/legacy/search');
})->name('search');
