<?php

// shop
\Legacy\System\Classes\LegacyRoute::add('/shop', function () {
	\Legacy\System\Classes\LegacyView::setPage('shop');
	\Legacy\System\Classes\LegacyView::$title = 'Negozio';
	\Legacy\System\Classes\LegacyView::$location = 'shop';
	\Legacy\System\Classes\LegacyView::show();
});

// item
\Legacy\System\Classes\LegacyRoute::add(['/shop/item', '/item'], function () {
	\Legacy\System\Classes\LegacyView::setPage('item');
	\Legacy\System\Classes\LegacyView::$location = 'shop';
	\Legacy\System\Classes\LegacyView::show();
});

// promotions
\Legacy\System\Classes\LegacyRoute::add('/promotions', function () {
	\Legacy\System\Classes\LegacyView::setPage('promotions');
	\Legacy\System\Classes\LegacyView::$title = 'Promozioni';
	\Legacy\System\Classes\LegacyView::$location = 'promotions';
	\Legacy\System\Classes\LegacyView::show();
});

//search
\Legacy\System\Classes\LegacyRoute::add('/search', function () {
	\Legacy\System\Classes\LegacyView::setPage('search');
	\Legacy\System\Classes\LegacyView::show();
});

// wishlist
\Legacy\System\Classes\LegacyRoute::add('/wishlist', function () {
	\Legacy\System\Classes\LegacyView::setPage('wishlist');
	\Legacy\System\Classes\LegacyView::$middlewares = ['logged'];
	\Legacy\System\Classes\LegacyView::show();
});
