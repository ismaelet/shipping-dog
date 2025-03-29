<?php

EcommerceSetup::execute();

class EcommerceSetup {
	// routes
	private static function initializeRoutes() {
		\Legacy\System\Classes\Logic::$dependencies['profile'] = 'main';
		\Legacy\System\Classes\Logic::$dependencies['cart'] = 'main';
		\Legacy\System\Classes\Logic::$priorities['profile'] = 60;
		\Legacy\System\Classes\Logic::$priorities['cart'] = 80;
	}

	// execute the setup
	public static function execute() {
		self::initializeRoutes();
	}
}
