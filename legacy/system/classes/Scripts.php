<?php

namespace Legacy\System\Classes;

class Scripts {

	private $list = [];

	function __construct() {
		global $view;

		// add framework scripts
		$files = glob(SERVER_ROOT . '/system/scripts/*.js');
		foreach ($files as $file) $this->add($this->getFileName($file), 'system');

		// add app scripts
		$files = glob(SERVER_ROOT . '/app/scripts/*.js');
		foreach ($files as $file) $this->add($this->getFileName($file), 'app');

		// add page script
		$this->add(\Legacy\System\Classes\LegacyView::$name, 'page', 4);

		// add additional page scripts
		$files = glob(SERVER_ROOT . '/pages/scripts/-expands-' . \Legacy\System\Classes\LegacyView::$name . '/*.js');
		foreach ($files as $file) $this->add('-expands-' . \Legacy\System\Classes\LegacyView::$name . '/' . $this->getFileName($file), 'page', 5);
	}

	// load a script
	public function add($fileName, $type, $priority = false) {
		$file = false;

		switch ($type) {
			case 'system':
				$file = '/system/scripts/' . $fileName . '.js';
				if (!$priority) $priority = 1;
				break;
			case 'app':
				$file = '/app/scripts/' . $fileName . '.js';
				if (!$priority) $priority = 3;
				break;
			case 'template':
				if (file_exists(SERVER_ROOT . $fetchingFile = '/app/templates/scripts/' . $fileName . '.js')) {
					$file = $fetchingFile;
					if (!$priority) $priority = 3;
				} else if (file_exists(SERVER_ROOT . $fetchingFile = '/system/templates/scripts/' . $fileName . '.js')) {
					$file = $fetchingFile;
					if (!$priority) $priority = 2;
				}
				break;
			case 'page':
				if (file_exists(SERVER_ROOT . $fetchingFile = '/pages/scripts/' . $fileName . '.js')) {
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

	// load all the scripts
	public function load() {
		$list = [];

		sort_subvalue($this->list, 0);

		foreach ($this->list as $style) $list[] = '<script src="' . CLIENT_ROOT . $style[1] . '"></script>';

		return implode(PHP_EOL, $list);
	}

	// get the file name
	private function getFileName($fileUrl) {
		return explode('.js', explode_last('/', $fileUrl))[0];
	}
}
