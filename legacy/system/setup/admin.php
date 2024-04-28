<?php

AdminSetup::execute();

class AdminSetup {
	// routes
	private static function initializeRoutes() {
		\Legacy\System\Classes\Logic::$dependencies['admin'] = 'void';
		\Legacy\System\Classes\Logic::$dependencies['admin-list'] = 'admin';
		\Legacy\System\Classes\Logic::$dependencies['admin-form'] = 'admin';
		\Legacy\System\Classes\Logic::$dependencies['admin-advanced'] = 'admin';
		\Legacy\System\Classes\Logic::$priorities['admin'] = 60;
		\Legacy\System\Classes\Logic::$priorities['admin-list'] = 200;
		\Legacy\System\Classes\Logic::$priorities['admin-form'] = 200;
		\Legacy\System\Classes\Logic::$priorities['admin-advanced'] = 65;
	}

	public static function execute() {
		self::initializeRoutes();
	}
}
