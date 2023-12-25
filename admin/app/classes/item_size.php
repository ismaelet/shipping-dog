<?php

class Size {

	public $id;
	public $slug;
	public $name;
	public $price;

	function __construct($id) {

		$row = DB::row('* FROM items_sizes WHERE ID = ?', [$id]);

		$this->id = $id;
		$this->slug = $row['tag'];
		$this->name = $row['name'];
		$this->price = $row['price'];
	}
}
