<?php

class Middlewares {

	private static $middlewares = [];

	public static function initialize() {
		$defaultMiddlewares = [
			'public' => function () {
				global $user;
				$public = sys_config('static') ?: DBOld::find('value FROM config WHERE name = ?', ['public']);
				if (!$public && !is_admin()) redirect_old('/offline');
			},

			'not-public' => function () {
				$public = sys_config('static') ?: DBOld::find('value FROM config WHERE name = ?', ['public']);
				if ($public) redirect_old('/');
			},

			'wip' => function () {
				redirect_old('/wip');
			},

			'logged' => function () {
				if (!auth()->check()) redirect_old('/login');
			},

			'not-logged' => function () {
				if (sys_config('static') || auth()->check()) redirect_old(sys_config('logged-redirect') ?: '/');
			},

			'admin' => function () {
				global $user;
				if (!is_admin()) redirect_old('/login');
			},

			'not-admin' => function () {
				global $user;
				if (is_admin()) redirect_old(sys_config('logged-redirect') ?: '/');
			},
		];

		foreach ($defaultMiddlewares as $middlewareName => $middlewareFunction) {
			self::add($middlewareName, $middlewareFunction);
		}
	}

	public static function add($middlewareName, $middlewareFunction) {
		self::$middlewares[$middlewareName] = $middlewareFunction;
	}

	public static function run($middlewareName) {
		$middlewareFunction = self::$middlewares[$middlewareName] ?? false;

		if ($middlewareFunction) {
			call_user_func_array(self::$middlewares[$middlewareName], []);
		} else {
			exit('Access denied: ' . $middlewareName);
		}
	}
}
