<?php

use DatabaseObject;

class User {

	use DatabaseObject;

	public const TABLE = 'users';
	public const INDEX = 'id';

	public $id;
	public $username;
	public $first_name;

	/*
	* Get methods
	*/
	private function getFullName() {
		$fullName = [];

		if ($this->first_name) $fullName[] = $this->first_name;
		if ($this->last_name) $fullName[] = $this->last_name;

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
			$this->userfirst_name = ucfirst($row['first_name']);
			$this->userlast_name = ucfirst($row['last_name']);
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
				case 'first_name':
					return $this->data['first_name'] = $this->getfirst_name();
				case 'last_name':
					return $this->data['last_name'] = $this->getlast_name();
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

	private function getfirst_name() {
		global $customer;

		$first_name = $this->userfirst_name;

		if (
			!$first_name &&
			isset($customer) &&
			$customer->id &&
			$customer->lastAddress->id
		) {
			$first_name = $customer->lastAddress->first_name;
		}

		return $first_name;
	}

	private function getlast_name() {
		global $customer;

		$last_name = $this->userlast_name;

		if (
			!$last_name &&
			isset($customer) &&
			$customer->id &&
			$customer->lastAddress->id
		) {
			$last_name = $customer->lastAddress->last_name;
		}

		return $last_name;
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
