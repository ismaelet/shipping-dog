<?php

namespace Ecommerce\Product;

use DBOld;

class Size {

	public const TABLE = 'items_sizes';
	public const INDEX = 'ID';

	public $id = null;
	public $slug;
	public $name;
	public $price;

	function __construct(int $id) {
		if ($row = DBOld::row('* FROM ' . self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$id])) {
			$this->id = $id;
			$this->slug = $row['tag'];
			$this->name = $row['name'];
			$this->price = $row['price'];
		}
	}
}
