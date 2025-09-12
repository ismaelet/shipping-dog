<?php

namespace App\Legacy\System;

use Legacy\System\Traits\DatabaseObject;

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
}
