<?php

use Legacy\Classes\Ecommerce\Customer;

EcommerceSetup::execute();

class EcommerceSetup {
	// routes
	private static function initializeRoutes() {
		\Legacy\System\Classes\Logic::$dependencies['profile'] = 'main';
		\Legacy\System\Classes\Logic::$dependencies['cart'] = 'main';
		\Legacy\System\Classes\Logic::$priorities['profile'] = 60;
		\Legacy\System\Classes\Logic::$priorities['cart'] = 80;
	}

	// customer
	private static function initializeCustomer() {
		global $customer;

		$customerId = null;
		$customerId = $_SESSION['customerId'] ?? null;

		$customer = new Customer($customerId);
	}

	// execute the setup
	public static function execute() {
		self::initializeRoutes();
		self::initializeCustomer();
	}
}
