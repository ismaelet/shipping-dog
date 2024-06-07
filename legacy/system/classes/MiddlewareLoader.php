<?php

namespace Legacy\System\Classes;

use App\Models\Framework\Configuration;

class MiddlewareLoader {

	private static $middlewares = [];

	public static function __constructStatic() {
		$defaultMiddlewareLoader = [
			'public' => function () {
			},

			'not-public' => function () {
			},

			'wip' => function () {
			},

			'logged' => function () {
				if (!auth()->check()) redirect_old('/login');
			},

			'not-logged' => function () {
				if (sys_config('static') || auth()->check()) redirect_old(sys_config('logged-redirect') ?: '/');
			},

			'admin' => function () {
				if (!is_admin()) redirect_old('/login');
			},

			'not-admin' => function () {
				if (is_admin()) redirect_old(sys_config('logged-redirect') ?: '/');
			},
		];

		foreach ($defaultMiddlewareLoader as $middlewareName => $middlewareFunction) {
			self::add($middlewareName, $middlewareFunction);
		}
	}

	public static function add($middlewareName, $middlewareFunction) {
		self::$middlewares[$middlewareName] = $middlewareFunction;
	}

	public static function run($middlewareName) {
		$middlewareFunction = self::$middlewares[$middlewareName] ?? null;

		if ($middlewareFunction) {
			call_user_func_array($middlewareFunction, []);
		} else {
			exit('Access denied: ' . $middlewareName);
		}
	}
}
