<?php

namespace Ecommerce;

use DBOld;
use DynamicProperties;

class Address {

	use DynamicProperties;

	public const TABLE = 'addresses';
	public const INDEX = 'id';
	public const CUSTOMER_INDEX = 'buyer';
	public const CART_INDEX = 'cartID';

	public $id = null;
	public $fullname;
	public $address;
	public $number;
	public $city;
	public $region;
	public $zip;
	public $phone;
	public $phonePrefix;
	public $phoneNumber;
	public $email;
	public $exchange;
	public $secretNumber;

	function __construct($id) {
		if ($row = DBOld::row('* FROM addresses WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->fullname = $row['aname'];
			$this->address = str_replace("\'", "'", $row['address']);
			$this->number = $row['number'];
			$this->city = $row['city'];
			$this->region = $row['region'];
			$this->zip = str_pad($row['zip'], 5, ' ', STR_PAD_LEFT);
			$this->phone = $row['phone'];
			$this->phonePrefix = substr($row['phone'], 1, 2);
			$this->phoneNumber = str_replace('-', '', substr($row['phone'], 5));
			$this->email = $row['email'];
			$this->exchange = $row['exchange'];
			$this->secretNumber = $row['security'];
		}
	}

	/*
	* Get methods
	*/
	private function getFirstName() {
		return explode(' ', $this->fullname)[0];
	}

	private function getLastName() {
		return explode(' ', $this->fullname, 2)[1] ?? '';
	}

	private function getFullAddress() {
		$fullAddress = $this->fullname;
		if ($this->address) $fullAddress .= '<br>' . $this->address;
		if ($this->number) $fullAddress .= ', ' . $this->number;
		if ($this->city) $fullAddress .= '<br>' . $this->zip . ' ' . $this->city;
		if ($this->region) $fullAddress .= ' (' . $this->region . ')';

		return $fullAddress;
	}

	private function getContacts() {
		$contacts = $this->email;
		if ($this->phone) $contacts .= '<br>' . $this->phone;

		return $contacts;
	}
}
