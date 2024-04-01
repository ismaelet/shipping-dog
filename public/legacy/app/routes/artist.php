<?php

// artist shop
Route::add('/artist', function () {
	View::setPage('artist');
	View::$location = 'artist';
	View::show();
});

// collection
Route::add('/artist/collection', function () {
	View::setPage('artist/collection');
	View::$location = 'artist';
	View::show();
});

// start selling
Route::add('/sell', function () {
	View::setPage('manage/sell');
	View::$location = 'sell';
	View::$title = 'Vendi';
	View::$template = 'form';
	View::$middlewares = ['logged'];
	View::show();
});

// new artist
Route::add('/manage/new', function () {
	View::setPage('manage/new');
	View::$location = 'artist';
	View::$template = 'form';
	View::show();
});

// manage - general settings
Route::add(['/manage', '/manage/settings', '/manage/settings/general'], function () {
	View::setPage('manage/settings/general');
	View::$location = 'artist';
	View::$title = 'Gestione';
	View::$template = 'manage';
	View::show();
});

// manage - portfolio
Route::add(['/manage/portfolio', '/manage/portfolio/list'], function () {
	View::setPage('manage/portfolio/list');
	View::$location = 'artist';
	View::$title = 'Portfolio';
	View::$template = 'manage';

	View::show();
});

// manage - upload a design
Route::add('/manage/portfolio/add', function () {
	View::setPage('manage/portfolio/add');
	View::$location = 'artist';
	View::$title = 'Carica lavoro';
	View::$template = 'manage';
	View::show();
});

// manage - edit a product
Route::add('/manage/portfolio/product', function () {
	View::setPage('manage/portfolio/product');
	View::$location = 'artist';
	View::$template = 'manage';
	View::show();
});

// manage - edit a design
Route::add('/manage/portfolio/edit', function () {
	View::setPage('manage/portfolio/edit');
	View::$location = 'artist';
	View::show();
});

// manage - customers pics list
Route::add(['/manage/pics', '/manage/pics/list'], function () {
	View::setPage('manage/pics/list');
	View::$location = 'artist';
	View::$title = 'Foto Clienti';
	View::$template = 'manage';
	View::show();
});

// manage - customers pics add
Route::add('/manage/pics/add', function () {
	View::setPage('manage/pics/add');
	View::$location = 'artist';
	View::$title = 'Foto Clienti';
	View::$template = 'manage';
	View::show();
});

// manage - sales list
Route::add(['/manage/sales', '/manage/sales/list'], function () {
	View::setPage('manage/sales/list');
	View::$location = 'artist';
	View::$title = 'Lista Vendite';
	View::$template = 'manage';
	View::show();
});

// manage - prices
Route::add('/manage/sales/prices', function () {
	View::setPage('manage/sales/prices');
	View::$location = 'artist';
	View::$title = 'Prezzi';
	View::$template = 'manage';
	View::show();
});

// manage - request a payment
Route::add('/manage/payments/request', function () {
	View::setPage('manage/payments/request');
	View::$title = 'Richiedi un pagamento';
	View::$location = 'artist';
	View::$template = 'form';
	View::show();
});
