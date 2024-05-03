<?php

// Load resources

use Legacy\System\Classes\Setup;

require_once __DIR__ . '/../../../vendor/autoload.php';

// Load environment variables
(Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'))->load();

// Set the error reporting
if (env('APP_DEBUG') == 'true') {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL ^ E_DEPRECATED);
}

// define class autoloader
spl_autoload_register(function ($name) {
	$name = str_replace('\\', '/', $name);

	switch (true) {
		case strpos($name, 'App/Models') === 0 || strpos($name, 'App/Helpers') === 0:
			include_once base_path() . lcfirst($name) . '.php';
			break;
		case strpos($name, 'Legacy/Models') === 0:
			include_once base_path() . '/legacy/app/Models/' . str_replace('Legacy/Models/', '', $name) . '.php';
			break;
		case strpos($name, 'Legacy/Classes') === 0:
			include_once base_path() . '/legacy/app/classes/' . str_replace('Legacy/Classes/', '', $name) . '.php';
			break;
		case strpos($name, 'Legacy/View') === 0:
			include_once base_path() . '/legacy/app/View/' . str_replace('Legacy/View/', '', $name) . '.php';
			break;
		case strpos($name, 'Legacy/System/Classes') === 0:
			include_once base_path() . '/legacy/system/classes/' . str_replace('Legacy/System/Classes/', '', $name) . '.php';
			break;
		case strpos($name, 'Legacy/System/Traits') === 0:
			include_once base_path() . '/legacy/system/traits/' . str_replace('Legacy/System/Traits/', '', $name) . '.php';
			break;
	}

	if (method_exists($name, '__constructStatic')) {
		$name::__constructStatic();
	}
});

// Load resources
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/vendor-config.php';

// render
if (!defined('SERVER_ROOT')) {
	Setup::execute();
}
