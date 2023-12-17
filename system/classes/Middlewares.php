<?php

class Middlewares {

	private static $middlewares = [];

	public static function initialize() {
		$defaultMiddlewares = [
			'public' => function () {
				global $user;
				$public = sys_config('static') ?: DB::find('value FROM config WHERE name=?', ['public']);
				if (!$public && !$user->admin) redirect('/offline');
			},

			'not-public' => function () {
				$public = sys_config('static') ?: DB::find('value FROM config WHERE name=?', ['public']);
				if ($public) redirect('/');
			},

			'wip' => function () {
				redirect('/wip');
			},

			'logged' => function () {
				if (!logged()) redirect('/login');
			},

			'not-logged' => function () {
				if (sys_config('static') || logged()) redirect(sys_config('logged-redirect') ?: '/');
			},

			'admin' => function () {
				global $user;
				if (!$user->admin) redirect('/login');
			},

			'not-admin' => function () {
				global $user;
				if ($user->admin) redirect(sys_config('logged-redirect') ?: '/');
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
