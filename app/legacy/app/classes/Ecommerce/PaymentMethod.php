<?php

namespace Legacy\Classes\Ecommerce;

use LegacyDB;

class PaymentMethod {

	public $id;
	public $name;

	function __construct($id) {
		if ($row = \Legacy\System\Classes\LegacyDB::row('* FROM sys_payment WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->name = $row['name'];
		}
	}

	function __get($name) {
		switch ($name) {
			case '':
				return $this->temp = 0;
				break;
		}
	}
}
