<?php

// ajax calls
RouteOld::add('/ajax/*', function ($path) {
	$module = explode('?', explode('ajax/', $path)[1])[0];

	if (file_exists($file = SERVER_ROOT . '/app/functions/ajax/' . $module . '.php')) $ajaxFile = $file;
	else if (file_exists($file = SERVER_ROOT . '/system/functions/ajax/' . $module . '.php')) $ajaxFile = $file;

	include_once($ajaxFile);
});

// api calls
RouteOld::add('/api/*', function () {
	include_once(SERVER_ROOT . '/system/setup/api.php');
});

// favicon
RouteOld::add('/favicon.ico', function () {
	include(SERVER_ROOT . '/app/assets/favicon/favicon.ico');
});
