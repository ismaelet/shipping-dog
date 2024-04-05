<?php

// artist shop
RouteOld::add('/artist', function () {
	ViewOld::setPage('artist');
	ViewOld::$location = 'artist';
	ViewOld::show();
});

// collection
RouteOld::add('/artist/collection', function () {
	ViewOld::setPage('artist/collection');
	ViewOld::$location = 'artist';
	ViewOld::show();
});

// start selling
RouteOld::add('/sell', function () {
	ViewOld::setPage('manage/sell');
	ViewOld::$location = 'sell';
	ViewOld::$title = 'Vendi';
	ViewOld::$template = 'form';
	ViewOld::$middlewares = ['logged'];
	ViewOld::show();
});

// new artist
RouteOld::add('/manage/new', function () {
	ViewOld::setPage('manage/new');
	ViewOld::$location = 'artist';
	ViewOld::$template = 'form';
	ViewOld::show();
});

// manage - general settings
RouteOld::add(['/manage', '/manage/settings', '/manage/settings/general'], function () {
	ViewOld::setPage('manage/settings/general');
	ViewOld::$location = 'artist';
	ViewOld::$title = 'Gestione';
	ViewOld::$template = 'manage';
	ViewOld::show();
});

// manage - portfolio
RouteOld::add(['/manage/portfolio', '/manage/portfolio/list'], function () {
	ViewOld::setPage('manage/portfolio/list');
	ViewOld::$location = 'artist';
	ViewOld::$title = 'Portfolio';
	ViewOld::$template = 'manage';

	ViewOld::show();
});

// manage - upload a design
RouteOld::add('/manage/portfolio/add', function () {
	ViewOld::setPage('manage/portfolio/add');
	ViewOld::$location = 'artist';
	ViewOld::$title = 'Carica lavoro';
	ViewOld::$template = 'manage';
	ViewOld::show();
});

// manage - edit a product
RouteOld::add('/manage/portfolio/product', function () {
	ViewOld::setPage('manage/portfolio/product');
	ViewOld::$location = 'artist';
	ViewOld::$template = 'manage';
	ViewOld::show();
});

// manage - edit a design
RouteOld::add('/manage/portfolio/edit', function () {
	ViewOld::setPage('manage/portfolio/edit');
	ViewOld::$location = 'artist';
	ViewOld::show();
});

// manage - customers pics list
RouteOld::add(['/manage/pics', '/manage/pics/list'], function () {
	ViewOld::setPage('manage/pics/list');
	ViewOld::$location = 'artist';
	ViewOld::$title = 'Foto Clienti';
	ViewOld::$template = 'manage';
	ViewOld::show();
});

// manage - customers pics add
RouteOld::add('/manage/pics/add', function () {
	ViewOld::setPage('manage/pics/add');
	ViewOld::$location = 'artist';
	ViewOld::$title = 'Foto Clienti';
	ViewOld::$template = 'manage';
	ViewOld::show();
});

// manage - sales list
RouteOld::add(['/manage/sales', '/manage/sales/list'], function () {
	ViewOld::setPage('manage/sales/list');
	ViewOld::$location = 'artist';
	ViewOld::$title = 'Lista Vendite';
	ViewOld::$template = 'manage';
	ViewOld::show();
});

// manage - prices
RouteOld::add('/manage/sales/prices', function () {
	ViewOld::setPage('manage/sales/prices');
	ViewOld::$location = 'artist';
	ViewOld::$title = 'Prezzi';
	ViewOld::$template = 'manage';
	ViewOld::show();
});

// manage - request a payment
RouteOld::add('/manage/payments/request', function () {
	ViewOld::setPage('manage/payments/request');
	ViewOld::$title = 'Richiedi un pagamento';
	ViewOld::$location = 'artist';
	ViewOld::$template = 'form';
	ViewOld::show();
});
