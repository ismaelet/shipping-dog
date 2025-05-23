<?php

namespace App\Legacy\Ecommerce\Product;

use App\Legacy\System\DB;

class Size {

	public const TABLE = 'items_sizes';
	public const INDEX = 'id';

	public $id = null;
	public $slug;
	public $name;
	public $price;

	function __construct(int $id) {
		if ($row = DB::row('* FROM ' . self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$id])) {
			$this->id = $id;
			$this->slug = $row['tag'];
			$this->name = $row['name'];
			$this->price = $row['price'];
		}
	}
}
