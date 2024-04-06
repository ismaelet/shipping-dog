<?php

AdminSetup::execute();

class AdminSetup {
	// routes
	private static function initializeRoutes() {
		Logic::$dependencies['admin'] = 'void';
		Logic::$dependencies['admin-list'] = 'admin';
		Logic::$dependencies['admin-form'] = 'admin';
		Logic::$dependencies['admin-advanced'] = 'admin';
		Logic::$priorities['admin'] = 60;
		Logic::$priorities['admin-list'] = 200;
		Logic::$priorities['admin-form'] = 200;
		Logic::$priorities['admin-advanced'] = 65;
	}

	public static function execute() {
		self::initializeRoutes();
	}
}
