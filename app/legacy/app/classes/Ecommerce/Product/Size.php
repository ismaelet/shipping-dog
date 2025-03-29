<?php

namespace Legacy\Classes\Ecommerce\Product;

use LegacyDB;

class Size {

	public const TABLE = 'items_sizes';
	public const INDEX = 'id';

	public $id = null;
	public $slug;
	public $name;
	public $price;

	function __construct(int $id) {
		if ($row = \Legacy\System\Classes\LegacyDB::row('* FROM ' . self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$id])) {
			$this->id = $id;
			$this->slug = $row['tag'];
			$this->name = $row['name'];
			$this->price = $row['price'];
		}
	}
}
