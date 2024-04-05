<?php

use DatabaseObject;

class User {

	use DatabaseObject;

	public const TABLE = 'users';
	public const INDEX = 'id';

	public $id;
	public $username;
	public $firstName;

	/*
	* Get methods
	*/
	private function getFullName() {
		$fullName = [];

		if ($this->firstName) $fullName[] = $this->firstName;
		if ($this->lastName) $fullName[] = $this->lastName;

		return implode(' ', $fullName);
	}

	private function getEmailIsUsername() {
		return !$this->username || $this->username == $this->email;
	}

	/*

	function __construct($id) {
		if ($row = DBOld::row('* FROM users WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->username = $row['username'] ?: $row['email'];
			$this->userFirstName = ucfirst($row['firstName']);
			$this->userLastName = ucfirst($row['lastName']);
			$this->email = strtolower($row['email']);
			$this->groupId = $row['groupId'] ?? null;
			$this->admin = $row['admin'];
		}
	}

	function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		} else {
			switch ($name) {
					// basic data
				case 'firstName':
					return $this->data['firstName'] = $this->getFirstName();
				case 'lastName':
					return $this->data['lastName'] = $this->getLastName();
				case 'fullName':
					return $this->data['fullName'] = $this->getFullName();
				case 'phone':
					return $this->data['phone'] = $this->getPhone();

					// images
				case 'icon':
					return $this->data['icon'] = $this->icon();
			}
		}
	}

	private function getFirstName() {
		global $customer;

		$firstName = $this->userFirstName;

		if (
			!$firstName &&
			isset($customer) &&
			$customer->id &&
			$customer->lastAddress->id
		) {
			$firstName = $customer->lastAddress->firstName;
		}

		return $firstName;
	}

	private function getLastName() {
		global $customer;

		$lastName = $this->userLastName;

		if (
			!$lastName &&
			isset($customer) &&
			$customer->id &&
			$customer->lastAddress->id
		) {
			$lastName = $customer->lastAddress->LastName;
		}

		return $lastName;
	}

	private function icon() {
		$icon = '/uploads/profile-icons/' . $this->id . '.jpg';

		if (!file_exists(SERVER_ROOT . $icon)) $icon = '/uploads/profile-icons/0.jpg';

		return $icon;
	}

	private function getPhone() {
		$customerId = DBOld::find('id FROM ecommerce_customers WHERE userId=?', [$this->id]);
		$phone = DBOld::find('phone FROM ecommerce_addresses WHERE customerId=? ORDER BY time DESC', [$customerId]);

		return $phone;
	}
	*/
}
