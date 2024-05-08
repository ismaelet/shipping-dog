<?php

namespace Legacy\System\Classes;

class User {

	use \Legacy\System\Traits\DatabaseObject;

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
}
