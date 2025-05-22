<?php

namespace App\Legacy\System;

class StyleLoader {

	private static $list = [];

	public static function __constructStatic() {
		$file = 'legacy.css?v=' . filemtime(base_path() . '/public/legacy.css');

		self::$list[] = [0, $file];

		$file = 'main.css?v=' . filemtime(base_path() . '/public/main.css');

		self::$list[] = [0, $file];
	}

	// add a style
	public static function add($fileName, $type, $priority = null) {
		$file = null;

		switch ($type) {
			case 'template':
				if (file_exists(base_path() . '/public' . $fetchingFile = '/templates/styles/' . $fileName . '.css')) {
					$priority = $priority ?: 3;
					$file = $fetchingFile;
				}

				self::addAdditionalTemplateStyles($fileName);

				break;

			case 'page':
				if (file_exists(base_path() . '/public' . $fetchingFile = '/pages/styles/' . $fileName . '.css')) {
					$priority = $priority ?: 4;
					$file = $fetchingFile;
				}
				break;
		}

		// store the file URL in the list
		if ($file) {
			$file .= '?v=' . filemtime(base_path() . '/public' . $file);
			self::$list[] = [$priority, $file];
		}
	}

	// add template style
	private static function addAdditionalTemplateStyles($templateName) {
		$additionalSystemStyles = glob(base_path() . '/public' . '/templates/styles/-expands-' . $templateName . '/*.css');

		foreach ($additionalSystemStyles as $additionalStyle) {
			self::add($additionalStyle, 'template-expansion', 2);
		}
	}

	// add the page style
	public static function addPageStyles($pageName) {
		$additionalStyles = glob(base_path() . '/public' . '/pages/styles/-expands-' . $pageName . '/*.css');

		self::add($pageName, 'page');

		foreach ($additionalStyles as $additionalStyle) {
			self::add('-expands-' . $pageName . '/' . self::getFileName($additionalStyle), 'page', 5);
		}
	}

	// load all the styles
	public static function load() {
		self::__constructStatic();

		$list = [];

		sort_subvalue(self::$list, 0);

		foreach (self::$list as $style) $list[] = '<link href="' . asset($style[1]) . '" rel="stylesheet" type="text/css">';

		return implode(PHP_EOL, $list);
	}

	// get the file name
	private static function getFileName($fileUrl) {
		return explode('.css', explode_last_legacy('/', $fileUrl))[0];
	}
}
