<?php

namespace Ecommerce\Product;

use DBOld;

class Gender {

	public $id = null;
	public $slug;
	public $name;

	function __construct($id) {
		if ($row = DBOld::row('* FROM items_genders WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->slug = $row['tag'];
			$this->name = $row['name'];
		}
	}
}
