<?php

namespace Ecommerce;

use App\Models\Ecommerce\Seller as EcommerceSeller;
use DBOld;
use Ecommerce\Seller;
use DynamicProperties;

class Customer {

	use DynamicProperties;

	public const TABLE = 'users';
	public const INDEX = 'id';
	public const USER_INDEX = 'userId';


	/*
	* Database methods
	*/
	public static function new() {
		global $customer;

		$customerId = $_SESSION['customerId'] = 'T' . DBOld::add('temp_users (ID) VALUES (NULL)');
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

	private function getCart() {
		$cartId = DBOld::find(Cart::INDEX . ' FROM ' . Cart::TABLE . ' WHERE ' . Cart::CUSTOMER_INDEX . ' = ? AND status = 0', [$this->id]);
		return new Cart($cartId);
	}

	private function getConfirmedCarts() {
		return rows_to_objects(DBOld::select(Cart::INDEX . ' FROM ' . Cart::TABLE . ' WHERE ' . Cart::CUSTOMER_INDEX . ' = ? AND status <> 0 ORDER BY time DESC', [$this->id]), Cart::class);
	}

	private function getConfirmedCartsCount() {
		return count($this->confirmedCarts);
	}

	private function getDefaultAddressId() {
		return DBOld::find(Address::INDEX . ' FROM ' . Address::TABLE . ' WHERE ' . Address::CUSTOMER_INDEX . ' = ? ORDER BY ' . Address::INDEX . ' DESC', [$this->id]);
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
		return DBOld::exists(Seller::TABLE . ' WHERE ' . Seller::INDEX . ' = ?', [$this->id]);
	}

	/*
	* Set methods
	*/
	public function setCart(object $cart) {
		$this->data[] = [];
		$this->data['cart'] = $cart;

		return $this;
	}
}
