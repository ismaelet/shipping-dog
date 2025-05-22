<?php

namespace App\Legacy\System;

class TranslationInterface {
	use Singleton;

	private static $translations;

	/**
	 * Magic methods
	 */
	private function __construct() {
		$translations = [];
		$language = $_SESSION['language'];

		// get system translations
		if (file_exists($file = public_server_path('/system/translations/' . $language . '.php'))) {
			$translations = include_once($file);
		}

		// get app translations
		$appTranslationFiles = scan('/app/translations/' . $language, 'php');

		foreach ($appTranslationFiles as $appTranslationFile) {
			$translations = array_merge($translations, include_once($appTranslationFile));
		}

		self::$translations = $translations;
	}

	/**
	 * Misc methods
	 */
	public function translate(string $text = '') {
		return self::$translations[$text] ?? $text;
	}
}
