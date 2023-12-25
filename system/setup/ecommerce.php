<?php

use Ecommerce\Customer;

EcommerceSetup::execute();

class EcommerceSetup {
	// routes
	private static function initializeRoutes() {
		Logic::$dependencies['profile'] = 'main';
		Logic::$dependencies['cart'] = 'main';
		Logic::$priorities['profile'] = 60;
		Logic::$priorities['cart'] = 80;
	}

	// customer
	private static function initializeCustomer() {
		global $customer;

		$customerId = userId() ?: null;
		$customerId = $_SESSION['customerId'] ?? null;

		$customer = new Customer($customerId);
	}

	// execute the setup
	public static function execute() {
		self::initializeRoutes();
		self::initializeCustomer();
	}
}
