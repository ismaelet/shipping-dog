<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class FormController extends Controller {

	private static $addMethod = null;
	private static $editMethod = null;

	/**
	 * Set the add method
	 */
	public static function setAdd(callable $method): void {
		self::$addMethod = $method;
	}

	/**
	 * Set the edit method
	 */
	public static function setEdit(callable $method): void {
		self::$editMethod = $method;
	}

	/**
	 * Execute the stored add method
	 */
	public static function add(array $parameters = []): bool {
		if (self::$addMethod) {
			call_user_func(self::$addMethod, ...$parameters);

			return true;
		}

		return false;
	}

	/**
	 * Execute the stored edit method
	 */
	public static function edit(array $parameters = []): bool {
		if (self::$editMethod) {
			call_user_func(self::$editMethod, ...$parameters);

			return true;
		}

		return false;
	}
}
