<?php

class StyleLoader {

	private static $list = [];

	public static function __constructStatic() {
		// add framework styles
		$files = glob(SERVER_ROOT . '/system/styles/*.css');
		foreach ($files as $file) self::add(self::getFileName($file), 'system');

		// add app styles
		$files = glob(SERVER_ROOT . '/app/styles/*.css');
		foreach ($files as $file) self::add(self::getFileName($file), 'app');
	}

	// add a style
	public static function add($fileName, $type, $priority = null) {
		$file = null;

		switch ($type) {
			case 'system':
				$priority = $priority ?: 1;
				$file = '/system/styles/' . $fileName . '.css';
				break;
			case 'app':
				$priority = $priority ?: 3;
				$file = '/app/styles/' . $fileName . '.css';
				break;
			case 'template':
				if (file_exists(SERVER_ROOT . $fetchingFile = '/app/templates/styles/' . $fileName . '.css')) {
					$priority = $priority ?: 3;
					$file = $fetchingFile;
				} else if (file_exists(SERVER_ROOT . $fetchingFile = '/system/templates/styles/' . $fileName . '.css')) {
					$priority = $priority ?: 2;
					$file = $fetchingFile;
				}

				self::addAdditionalTemplateStyles($fileName);

				break;
			case 'page':
				if (file_exists(SERVER_ROOT . $fetchingFile = '/pages/styles/' . $fileName . '.css')) {
					$priority = $priority ?: 4;
					$file = $fetchingFile;
				}
				break;
			default:
				$file = force_base_url($fileName);
				if (!$priority) $priority = 6;
				break;
		}

		// store the file URL in the list
		if ($file) {
			$file .= '?v=' . filemtime(SERVER_ROOT . $file);
			self::$list[] = [$priority, $file];
		}
	}

	// add template style
	private static function addAdditionalTemplateStyles($templateName) {
		$additionalSystemStyles = glob(SERVER_ROOT . '/system/templates/styles/-expands-' . $templateName . '/*.css');
		$additionalAppStyles = glob(SERVER_ROOT . '/app/templates/styles/-expands-' . $templateName . '/*.css');

		foreach ($additionalSystemStyles as $additionalStyle) {
			self::add($additionalStyle, 'template-expansion', 2);
		}

		foreach ($additionalAppStyles as $additionalStyle) {
			self::add($additionalStyle, 'template-expansion', 3);
		}
	}

	// add the page style
	public static function addPageStyles($pageName) {
		$additionalStyles = glob(SERVER_ROOT . '/pages/styles/-expands-' . $pageName . '/*.css');

		self::add($pageName, 'page');

		foreach ($additionalStyles as $additionalStyle) {
			self::add('-expands-' . $pageName . '/' . self::getFileName($additionalStyle), 'page', 5);
		}
	}

	// load all the styles
	public static function load() {
		$list = [];

		sort_subvalue(self::$list, 0);

		foreach (self::$list as $style) $list[] = '<link href="' . CLIENT_ROOT . $style[1] . '" rel="stylesheet" type="text/css">';

		return implode(PHP_EOL, $list);
	}

	// get the file name
	private static function getFileName($fileUrl) {
		return explode('.css', explode_last('/', $fileUrl))[0];
	}
}
