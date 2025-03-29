<?php

function load_middleware($name) {

	$middleware = false;

	if (file_exists($file = SERVER_ROOT . '/app/middlewares/' . $name . '.php')) $middleware = $file;
	else if (file_exists($file = SERVER_ROOT . '/system/middlewares/' . $name . '.php')) $middleware = $file;

	if ($middleware) include_once($middleware);
}
