<?php

namespace App\Legacy\Ecommerce;

use LegacyDB;

class PaymentMethod {

	public $id;
	public $name;

	function __construct($id) {
		if ($row = DB::row('* FROM payment_methods WHERE id = ?', [$id])) {
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
