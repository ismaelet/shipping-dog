<?php

namespace Legacy\Classes\Ecommerce;

use App\Models\Ecommerce\Seller as EcommerceSeller;
use Legacy\Classes\Ecommerce\Seller;

class Customer {

	use \Legacy\System\Traits\DynamicProperties;

	public const TABLE = 'users';
	public const INDEX = 'id';
	public const USER_INDEX = 'userId';

	/*
	* Database methods
	*/
	public static function new() {
		global $customer;

		$customerId = $_SESSION['customerId'] = 'T' . \Legacy\System\Classes\LegacyDB::add('temp_users (ID) VALUES (NULL)');
		$customer = new self($customerId);

		return $customer;
	}

	/*
	* Get methods
	*/
	private function getId() {
		return auth()->id() ?: ($_SESSION['customerId'] ?? null);
	}

	private function getGuest() {
		return (bool) $this->id;
	}

	private function getConfirmedorders() {
		return rows_to_objects(\Legacy\System\Classes\LegacyDB::select(Order::INDEX . ' FROM ' . Order::TABLE . ' WHERE ' . Order::CUSTOMER_INDEX . ' = ? AND status <> 0 ORDER BY created_at DESC', [$this->id]), Order::class);
	}

	private function getConfirmedordersCount() {
		return count($this->confirmedorders);
	}

	private function getDefaultAddressId() {
		return \Legacy\System\Classes\LegacyDB::find(Address::INDEX . ' FROM ' . Address::TABLE . ' WHERE ' . Address::CUSTOMER_INDEX . ' = ? ORDER BY ' . Address::INDEX . ' DESC', [$this->id]);
	}

	private function getDefaultAddress() {
		return new Address($this->defaultAddressId);
	}

	private function getHasAddress() {
		return (bool) $this->getDefaultAddressId();
	}

	private function getCredit(): float {
		$seller = EcommerceSeller::find($this->id);
		return $seller ? $seller->credit() : 0;
	}

	private function getIsSeller() {
		return \Legacy\System\Classes\LegacyDB::exists(Seller::TABLE . ' WHERE ' . Seller::INDEX . ' = ?', [$this->id]);
	}

	/*
	* Set methods
	*/
	public function setCart(object $order) {
		$this->data[] = [];
		$this->data['cart'] = $order;

		return $this;
	}
}
