<?php

class Size {

	public $id;
	public $slug;
	public $name;
	public $price;

	function __construct($id) {

		$row = \Legacy\System\Classes\LegacyDB::row('* FROM items_sizes WHERE id = ?', [$id]);

		$this->id = $id;
		$this->slug = $row['tag'];
		$this->name = $row['name'];
		$this->price = $row['price'];
	}
}
