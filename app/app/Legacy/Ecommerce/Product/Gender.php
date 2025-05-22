<?php

namespace App\Legacy\Ecommerce\Product;

use LegacyDB;

class Gender {

	public $id = null;
	public $slug;
	public $name;

	function __construct($id) {
		if ($row = DB::row('* FROM items_genders WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->slug = $row['tag'];
			$this->name = $row['name'];
		}
	}
}
