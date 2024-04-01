<?php

// ajax calls
Route::add('/ajax/*', function ($path) {
	$module = explode('?', explode('ajax/', $path)[1])[0];

	if (file_exists($file = SERVER_ROOT . '/app/functions/ajax/' . $module . '.php')) $ajaxFile = $file;
	else if (file_exists($file = SERVER_ROOT . '/system/functions/ajax/' . $module . '.php')) $ajaxFile = $file;

	include_once($ajaxFile);
});

// api calls
Route::add('/api/*', function () {
	include_once(SERVER_ROOT . '/system/setup/api.php');
});

// favicon
Route::add('/favicon.ico', function () {
	include(SERVER_ROOT . '/app/assets/favicon/favicon.ico');
});
