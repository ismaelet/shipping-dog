<?php

namespace Legacy\System\Classes;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LegacyRoute {

	private static $routes = [];

	public static function add($paths, $function): void {

		$paths = (array) $paths;

		foreach ($paths as $path)
			array_push(self::$routes, [
				'path' => CLIENT_ROOT . $path,
				'function' => $function,
			]);
	}

	public static function run() {
		$path_found = false;
		$path = parse_url($_SERVER['REQUEST_URI'])['path'] ?? null;

		if ($path !== null) {
			foreach (self::$routes as $route) {
				if ((in_array($path, [$route['path'], $route['path'] . '/'])) || (strpos($route['path'], '*') && strpos($path, rtrim($route['path'], '/*')) !== false)) {
					$path_found = true;
					call_user_func_array($route['function'], [$path]);
					break;
				}
			}
		}

		// error 404
		if (!$path_found) {
			self::error404();
		}
	}

	public static function getRoutes(): array {
		return self::$routes;
	}

	public static function error404(): NotFoundHttpException {
		return abort(404);
	}
}
