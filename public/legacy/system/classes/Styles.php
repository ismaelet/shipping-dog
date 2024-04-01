<?php

class Styles {

	private $list = [];

	function __construct() {
		// add framework styles
		$files = glob(SERVER_ROOT . '/system/styles/*.css');
		foreach ($files as $file) $this->add($this->getFileName($file), 'system');

		// add app styles
		$files = glob(SERVER_ROOT . '/app/styles/*.css');
		foreach ($files as $file) $this->add($this->getFileName($file), 'app');

		// add page style
		$this->add(View::$name, 'page', 4);

		// add additional page styles
		$files = glob(SERVER_ROOT . '/pages/styles/-expands-' . View::$name . '/*.css');
		foreach ($files as $file) $this->add('-expands-' . View::$name . '/' . $this->getFileName($file), 'page', 5);
	}

	// add a style
	public function add($fileName, $type, $priority = false) {
		$file = false;

		switch ($type) {
			case 'system':
				$file = '/system/styles/' . $fileName . '.css';
				if (!$priority) $priority = 1;
				break;
			case 'app':
				$file = '/app/styles/' . $fileName . '.css';
				if (!$priority) $priority = 3;
				break;
			case 'template':
				if (file_exists(SERVER_ROOT . $fetchingFile = '/app/templates/styles/' . $fileName . '.css')) {
					$file = $fetchingFile;
					if (!$priority) $priority = 3;
				} else if (file_exists(SERVER_ROOT . $fetchingFile = '/system/templates/styles/' . $fileName . '.css')) {
					$file = $fetchingFile;
					if (!$priority) $priority = 2;
				}
				break;
			case 'page':
				if (file_exists(SERVER_ROOT . $fetchingFile = '/pages/styles/' . $fileName . '.css')) {
					$file = $fetchingFile;
					if (!$priority) $priority = 4;
				}
				break;
			case 'static':
				$file = $fileName;
				if (!$priority) $priority = 6;
				break;
		}

		// store the file URL in the list
		if ($file) {
			$file .= '?v=' . filemtime(SERVER_ROOT . $file);
			$this->list[] = [$priority, $file];
		}
	}

	// load all the styles
	public function load() {
		$list = [];

		sort_subvalue($this->list, 0);

		foreach ($this->list as $style) $list[] = '<link href="' . CLIENT_ROOT . $style[1] . '" rel="stylesheet" type="text/css">';

		return implode(PHP_EOL, $list);
	}

	// get the file name
	private function getFileName($fileUrl) {
		return explode('.css', explode_last('/', $fileUrl))[0];
	}
}
