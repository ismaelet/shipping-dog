<?php

// Load resources
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
		case strpos($name, 'App/Model') === 0 || strpos($name, 'App/Helpers') === 0:
			include_once __DIR__ . ('/../../../' . lcfirst($name) . '.php');
			break;
		case strpos($name, 'App') === 0:
			include_once __DIR__ . ('/../../' . lcfirst($name) . '.php');
			break;
		case file_exists($file = __DIR__ . ('/../../app/classes/' . $name . '.php')):
			include_once $file;
			break;
		case file_exists($file = __DIR__ . ('/../classes/' . $name . '.php')):
			include_once $file;
			break;
		case file_exists($file = __DIR__ . ('/../traits/' . $name . '.php')):
			include_once $file;
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
Setup::execute();

class Setup {
	// constants
	static private function initializeConstants() {
		global $sysConfig;

		define('SERVER_ROOT', explode('/system', str_replace("\\", "/", __DIR__))[0]);
		define('CLIENT_ROOT', '');
		define('CLIENT_URL', !empty($sysConfig['domain']) ? 'https://' . $sysConfig['domain'] : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . CLIENT_ROOT);
		define('ICONS_DIR', '/system/assets/icons/');
		define('APP_ICONS_DIR', '/app/assets/icons/');
		define('IMG_DIR', '/app/assets/images/');
		define('LOGO_DIR', '/app/assets/logo/');
	}

	// functions
	static private function initializeFunctions() {
		$modules = glob(SERVER_ROOT . '/app/Helpers/*.php', GLOB_BRACE);
		foreach ($modules as $module) include_once($module);

		$modules = glob(SERVER_ROOT . '/system/functions/*.php', GLOB_BRACE);
		foreach ($modules as $module) include_once($module);

		$modules = glob(SERVER_ROOT . '/app/functions/*.php', GLOB_BRACE);
		foreach ($modules as $module) include_once($module);
	}

	// routes
	static private function initializeRoutes() {
		foreach (scan('/system/routes', 'php') as $route) include_once($route);
		foreach (scan('/app/routes', 'php') as $route) include_once($route);
	}

	// connect
	static private function connect() {
		global $con;

		if (!sys_config('static')) {
			try {
				$con = new mysqli(sys_config('hostname'), sys_config('username'), sys_config('password'), sys_config('database'));
				$con->query('SET NAMES utf8');
			} catch (Exception $e) {
				$error = $e->getMessage();

				error_log('Connection error: ' . $error . PHP_EOL . 'On page: ' . $_SERVER['REQUEST_URI']);
				echo $error;
			}
		}
	}

	// migrate
	static private function migrate() {
		if (!sys_config('static') && sys_config('migrate')) {
			$tables = glob(SERVER_ROOT . '/system/migration/*.php');
			foreach ($tables as $table) include_once($table);

			$tables = glob(SERVER_ROOT . '/app/migration/*.php');
			foreach ($tables as $table) include_once($table);
		}
	}

	// language
	static private function sessionLanguage() {
		$acceptedLanguages = sys_config('languages');
		$defaultLanguage = sys_config('default-language');
		$browserLanguage = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
		$sessionLanguage = $_SESSION['language'] ?? null;
		$requestedLanguage = $_GET['lang'] ?? null;

		// remove unused session languages
		if ($sessionLanguage && !isset($acceptedLanguages[$sessionLanguage])) {
			$sessionLanguage = null;
			unset($_SESSION['language']);
		}

		// set the requested language
		if ($requestedLanguage && isset($acceptedLanguages[$requestedLanguage])) {
			$sessionLanguage = $_SESSION['language'] = $_GET['lang'];
		}

		// set the session language
		if (!$sessionLanguage) {
			$sessionLanguage = $_SESSION['language'] = isset($acceptedLanguages[$browserLanguage]) ? $browserLanguage : $defaultLanguage;
		}
	}

	// theme
	static private function sessionTheme() {
		$_SESSION['theme-color'] = ($_COOKIE['theme-color'] ?? sys_config('theme-color'));
	}

	// execute the setup
	static public function execute() {
		// load resources
		self::initializeConstants();
		self::initializeFunctions();

		// connect
		self::connect();
		self::initializeRoutes();

		// start session
		session_start();
		date_default_timezone_set(sys_config('timezone'));

		// setup the session
		self::sessionLanguage();
		self::sessionTheme();

		// other setups
		foreach (['admin', 'ecommerce'] as $setupFile) {
			if (file_exists($file = __DIR__ . '/' . $setupFile . '.php')) require_once($file);
		}

		// render page
		RouteOld::run();
	}
}
