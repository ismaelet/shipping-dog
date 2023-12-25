<?php

// login
Route::add('/login', function () {
	View::setPage('login');
	View::$title = 'Login';
	View::$middlewares = ['not-logged'];
	View::show();
});

// register
Route::add('/register', function () {
	View::setPage('register');
	View::$title = 'Registrati';
	View::$middlewares = ['not-logged'];
	View::show();
});

// logout
Route::add('/logout', function () {
	View::setPage('logout');
	View::$title = 'Logout';
	View::$template = 'form';
	View::$middlewares = ['logged'];
	View::show();
});

// recover
Route::add('/recover', function () {
	View::setPage('recover');
	View::$title = 'Recupera l\'accesso';
	View::$template = 'form';
	View::$middlewares = ['not-logged'];
	View::show();
});

// offiline
Route::add('/offline', function () {
	View::setPage('errors/offline');
	View::$title = 'Offline';
	View::$template = 'void';
	View::$middlewares = [];
	View::show();
});

// maintenance
Route::add('/wip', function () {
	View::setPage('errors/wip');
	View::$title = 'Under construction';
	View::$template = 'void';
	View::show();
});
