<?php

// login
\Legacy\System\Classes\LegacyRoute::add('/login', function () {
	\Legacy\System\Classes\LegacyView::setPage('login');
	\Legacy\System\Classes\LegacyView::$title = 'Login';
	\Legacy\System\Classes\LegacyView::$middlewares = ['not-logged'];
	\Legacy\System\Classes\LegacyView::show();
});

// register
\Legacy\System\Classes\LegacyRoute::add('/register', function () {
	\Legacy\System\Classes\LegacyView::setPage('register');
	\Legacy\System\Classes\LegacyView::$title = 'Registrati';
	\Legacy\System\Classes\LegacyView::$middlewares = ['not-logged'];
	\Legacy\System\Classes\LegacyView::show();
});

// logout
\Legacy\System\Classes\LegacyRoute::add('/logout', function () {
	\Legacy\System\Classes\LegacyView::setPage('logout');
	\Legacy\System\Classes\LegacyView::$title = 'Logout';
	\Legacy\System\Classes\LegacyView::$template = 'form';
	\Legacy\System\Classes\LegacyView::$middlewares = ['logged'];
	\Legacy\System\Classes\LegacyView::show();
});

// recover
\Legacy\System\Classes\LegacyRoute::add('/recover', function () {
	\Legacy\System\Classes\LegacyView::setPage('recover');
	\Legacy\System\Classes\LegacyView::$title = 'Recupera l\'accesso';
	\Legacy\System\Classes\LegacyView::$template = 'form';
	\Legacy\System\Classes\LegacyView::$middlewares = ['not-logged'];
	\Legacy\System\Classes\LegacyView::show();
});

// offiline
\Legacy\System\Classes\LegacyRoute::add('/offline', function () {
	\Legacy\System\Classes\LegacyView::setPage('errors/offline');
	\Legacy\System\Classes\LegacyView::$title = 'Offline';
	\Legacy\System\Classes\LegacyView::$template = 'void';
	\Legacy\System\Classes\LegacyView::$middlewares = [];
	\Legacy\System\Classes\LegacyView::show();
});

// maintenance
\Legacy\System\Classes\LegacyRoute::add('/wip', function () {
	\Legacy\System\Classes\LegacyView::setPage('errors/wip');
	\Legacy\System\Classes\LegacyView::$title = 'Under construction';
	\Legacy\System\Classes\LegacyView::$template = 'void';
	\Legacy\System\Classes\LegacyView::show();
});
