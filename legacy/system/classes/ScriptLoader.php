<?php

class ScriptLoader {

	private static $list = [];
	private static $headInlineScripts = [];
	private static $footInlineScripts = [];

	public static function __constructStatic() {
		$file = 'legacy.js?v=' . filemtime(base_path() . '/public/legacy.js');

		self::$list[] = [0, $file];
	}

	// load a script
	public static function add($fileName, $type, $priority = false) {
		$file = false;

		switch ($type) {
			case 'template':
				if (file_exists(base_path() . '/public' . $fetchingFile = '/templates/scripts/' . $fileName . '.js')) {
					$file = $fetchingFile;
					if (!$priority) $priority = 3;
				}
				break;
			case 'page':
				if (file_exists(base_path() . '/public' . $fetchingFile = '/pages/scripts/' . $fileName . '.js')) {
					$file = $fetchingFile;
					if (!$priority) $priority = 4;
				}
				break;
		}

		// store the file URL in the list
		if ($file) {
			$file .= '?v=' . filemtime(base_path() . '/public' . $file);
			self::$list[] = [$priority, $file];
		}
	}

	public static function addPageScripts($pageName) {
		$pageScripts = glob(base_path() . '/public' . '/pages/scripts/-expands-' . $pageName . '/*.js');

		self::add($pageName, 'page');

		foreach ($pageScripts as $pageScript) {
			self::add('-expands-' . $pageName . '/' . self::getFileName($pageScript), 'page', 5);
		}
	}

	// get the file name
	private static function getFileName($fileUrl) {
		return explode('.js', explode_last('/', $fileUrl))[0];
	}

	/*
	* Load the scripts
	*/
	// load all the scripts
	public static function load() {
		$list = [];

		sort_subvalue(self::$list, 0);

		foreach (self::$list as $style) $list[] = '<script src="' . asset($style[1]) . '"></script>';

		return implode(PHP_EOL, $list);
	}

	// load the inline script to be placed in the head
	public static function loadHeadInline() {
		return implode(PHP_EOL . PHP_EOL, self::$headInlineScripts);
	}

	// load the inline script to be placed at the end of the body
	public static function loadFootInline() {
		return implode(PHP_EOL . PHP_EOL, self::$footInlineScripts);
	}
}
