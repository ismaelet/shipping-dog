<?php

namespace Legacy\Classes\Ecommerce;

use LegacyDB;
use \Legacy\System\Traits\DynamicProperties;

class Address {

	use \Legacy\System\Traits\DynamicProperties;

	public const TABLE = 'addresses';
	public const INDEX = 'id';
	public const CUSTOMER_INDEX = 'user_id';
	public const CART_INDEX = 'cart_id';

	public $id = null;
	public $full_name;
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
		if ($row = \Legacy\System\Classes\LegacyDB::row('* FROM addresses WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->full_name = $row['full_name'];
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
	private function getfirst_name() {
		return explode(' ', $this->full_name)[0];
	}

	private function getlast_name() {
		return explode(' ', $this->full_name, 2)[1] ?? '';
	}

	private function getFullAddress() {
		$fullAddress = $this->full_name;
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
