<?php

namespace Ecommerce\Product;

use DB;

class Gender {

	public $id = null;
	public $slug;
	public $name;

	function __construct($id) {
		if ($row = DB::row('* FROM items_genders WHERE ID=?', [$id])) {
			$this->id = $id;
			$this->slug = $row['tag'];
			$this->name = $row['name_' . 'it'];
		}
	}
}
