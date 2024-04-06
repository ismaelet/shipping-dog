<?php

namespace Ecommerce;

use DBOld;

class PaymentMethod {

	public $id;
	public $name;

	function __construct($id) {
		if ($row = DBOld::row('* FROM sys_payment WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->name = $row['name_it'];
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
