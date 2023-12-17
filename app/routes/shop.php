<?php

// shop
Route::add('/shop', function () {
	View::setPage('shop');
	View::$title = 'Negozio';
	View::$location = 'shop';
	View::show();
});

// item
Route::add(['/shop/item', '/item'], function () {
	View::setPage('item');
	View::$location = 'shop';
	View::show();
});

// promotions
Route::add('/promotions', function () {
	View::setPage('promotions');
	View::$title = 'Promozioni';
	View::$location = 'promotions';
	View::show();
});

//search
Route::add('/search', function () {
	View::setPage('search');
	View::show();
});

// wishlist
Route::add('/wishlist', function () {
	View::setPage('wishlist');
	View::$middlewares = ['logged'];
	View::show();
});
