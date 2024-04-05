<?php

// shop
RouteOld::add('/shop', function () {
	ViewOld::setPage('shop');
	ViewOld::$title = 'Negozio';
	ViewOld::$location = 'shop';
	ViewOld::show();
});

// item
RouteOld::add(['/shop/item', '/item'], function () {
	ViewOld::setPage('item');
	ViewOld::$location = 'shop';
	ViewOld::show();
});

// promotions
RouteOld::add('/promotions', function () {
	ViewOld::setPage('promotions');
	ViewOld::$title = 'Promozioni';
	ViewOld::$location = 'promotions';
	ViewOld::show();
});

//search
RouteOld::add('/search', function () {
	ViewOld::setPage('search');
	ViewOld::show();
});

// wishlist
RouteOld::add('/wishlist', function () {
	ViewOld::setPage('wishlist');
	ViewOld::$middlewares = ['logged'];
	ViewOld::show();
});
