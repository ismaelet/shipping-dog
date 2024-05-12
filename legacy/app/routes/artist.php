<?php

// artist shop
\Legacy\System\Classes\LegacyRoute::add('/artist', function () {
	\Legacy\System\Classes\LegacyView::setPage('artist');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::show();
});

// collection
\Legacy\System\Classes\LegacyRoute::add('/artist/collection', function () {
	\Legacy\System\Classes\LegacyView::setPage('artist/collection');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::show();
});

// start selling
\Legacy\System\Classes\LegacyRoute::add('/sell', function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/sell');
	\Legacy\System\Classes\LegacyView::$location = 'sell';
	\Legacy\System\Classes\LegacyView::$title = 'Vendi';
	\Legacy\System\Classes\LegacyView::$template = 'form';
	\Legacy\System\Classes\LegacyView::$middlewares = ['logged'];
	\Legacy\System\Classes\LegacyView::show();
});

// new artist
\Legacy\System\Classes\LegacyRoute::add('/manage/new', function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/new');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$template = 'form';
	\Legacy\System\Classes\LegacyView::show();
});

// manage - general settings
\Legacy\System\Classes\LegacyRoute::add(['/manage', '/manage/settings', '/manage/settings/general'], function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/settings/general');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$title = 'Gestione';
	\Legacy\System\Classes\LegacyView::$template = 'manage';
	\Legacy\System\Classes\LegacyView::show();
});

// manage - portfolio
\Legacy\System\Classes\LegacyRoute::add(['/manage/portfolio', '/manage/portfolio/list'], function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/portfolio/list');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$title = 'Portfolio';
	\Legacy\System\Classes\LegacyView::$template = 'manage';

	\Legacy\System\Classes\LegacyView::show();
});

// manage - upload a design
\Legacy\System\Classes\LegacyRoute::add('/manage/portfolio/add', function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/portfolio/add');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$title = 'Carica lavoro';
	\Legacy\System\Classes\LegacyView::$template = 'manage';
	\Legacy\System\Classes\LegacyView::show();
});

// manage - edit a product
\Legacy\System\Classes\LegacyRoute::add('/manage/portfolio/product', function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/portfolio/product');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$template = 'manage';
	\Legacy\System\Classes\LegacyView::show();
});

// manage - edit a design
\Legacy\System\Classes\LegacyRoute::add('/manage/portfolio/edit', function () {
	redirect_old('/manage/portfolio/list');
});

// manage - customers pics list
\Legacy\System\Classes\LegacyRoute::add(['/manage/pics', '/manage/pics/list'], function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/pics/list');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$title = 'Foto Clienti';
	\Legacy\System\Classes\LegacyView::$template = 'manage';
	\Legacy\System\Classes\LegacyView::show();
});

// manage - customers pics add
\Legacy\System\Classes\LegacyRoute::add('/manage/pics/add', function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/pics/add');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$title = 'Foto Clienti';
	\Legacy\System\Classes\LegacyView::$template = 'manage';
	\Legacy\System\Classes\LegacyView::show();
});

// manage - sales list
\Legacy\System\Classes\LegacyRoute::add(['/manage/sales', '/manage/sales/list'], function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/sales/list');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$title = 'Lista Vendite';
	\Legacy\System\Classes\LegacyView::$template = 'manage';
	\Legacy\System\Classes\LegacyView::show();
});

// manage - prices
\Legacy\System\Classes\LegacyRoute::add('/manage/sales/prices', function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/sales/prices');
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$title = 'Prezzi';
	\Legacy\System\Classes\LegacyView::$template = 'manage';
	\Legacy\System\Classes\LegacyView::show();
});

// manage - request a payment
\Legacy\System\Classes\LegacyRoute::add('/manage/payments/request', function () {
	\Legacy\System\Classes\LegacyView::setPage('manage/payments/request');
	\Legacy\System\Classes\LegacyView::$title = 'Richiedi un pagamento';
	\Legacy\System\Classes\LegacyView::$location = 'artist';
	\Legacy\System\Classes\LegacyView::$template = 'form';
	\Legacy\System\Classes\LegacyView::show();
});
