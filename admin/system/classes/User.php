<?php

class User {

	private $data;

	public $id = null;
	public $username;
	public $email;
	public $groupId = null;
	public $admin = false;

	function __construct($id) {
		if ($this->data = DB::row('* FROM users WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->username = $this->data['username'] ?: $this->data['email'];
			$this->firstname = ucfirst($this->data['firstname']);
			$this->lastname = ucfirst($this->data['lastname']);
			$this->email = $this->data['email'];
			$this->groupId = $this->data['groupId'] ?? null;
			$this->admin = $this->data['admin'];
		}
	}

	function __get($name) {
		switch ($name) {
				// basic data
			case 'firstname':
				$this->setName();
				return $this->firstname;
				break;
			case 'lastname':
				$this->setName();
				return $this->lastname;
				break;
			case 'fullName':
				return $this->fullastName = $this->getFullName();
				break;
			case 'phone':
				return $this->phone = $this->getPhone();
				break;

				// images
			case 'icon':
				return $this->icon = $this->icon();
				break;
		}
	}

	private function setName() {
		global $customer;

		$this->firstname = $this->data['firstname'] ?? null;
		$this->lastname = $this->data['lastname'] ?? null;

		if (isset($customer) && !$this->firstname && $customer->id && $customer->lastAddress->id) {
			$this->firstname = $customer->lastAddress->firstname;
			$this->lastname = $customer->lastAddress->lastname;
		}

		$this->firstname = ucfirst($this->firstname);
		$this->lastname = ucfirst($this->lastname);
	}

	private function getFullName() {
		$name = [];

		if ($this->firstname) $name[] = $this->firstname;
		if ($this->lastname) $name[] = $this->lastname;

		return implode(' ', $name);
	}

	private function icon() {
		$icon = '/uploads/profile-icons/' . $this->id . '.jpg';

		if (!file_exists(SERVER_ROOT . $icon)) $icon = '/uploads/profile-icons/0.jpg';

		return $icon;
	}

	private function getPhone() {
		$customerId = DB::find('id FROM ecommerce_customers WHERE userId=?', [$this->id]);
		$phone = DB::find('phone FROM ecommerce_addresses WHERE customerId=? ORDER BY time DESC', [$customerId]);

		return $phone;
	}
}
