<?php

// login
RouteOld::add('/login', function () {
	ViewOld::setPage('login');
	ViewOld::$title = 'Login';
	ViewOld::$middlewares = ['not-logged'];
	ViewOld::show();
});

// register
RouteOld::add('/register', function () {
	ViewOld::setPage('register');
	ViewOld::$title = 'Registrati';
	ViewOld::$middlewares = ['not-logged'];
	ViewOld::show();
});

// logout
RouteOld::add('/logout', function () {
	ViewOld::setPage('logout');
	ViewOld::$title = 'Logout';
	ViewOld::$template = 'form';
	ViewOld::$middlewares = ['logged'];
	ViewOld::show();
});

// recover
RouteOld::add('/recover', function () {
	ViewOld::setPage('recover');
	ViewOld::$title = 'Recupera l\'accesso';
	ViewOld::$template = 'form';
	ViewOld::$middlewares = ['not-logged'];
	ViewOld::show();
});

// offiline
RouteOld::add('/offline', function () {
	ViewOld::setPage('errors/offline');
	ViewOld::$title = 'Offline';
	ViewOld::$template = 'void';
	ViewOld::$middlewares = [];
	ViewOld::show();
});

// maintenance
RouteOld::add('/wip', function () {
	ViewOld::setPage('errors/wip');
	ViewOld::$title = 'Under construction';
	ViewOld::$template = 'void';
	ViewOld::show();
});
