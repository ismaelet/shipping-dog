<?php

class Color {

	public $id = 0;
	public $slug = 'emp';
	public $name = '';
	public $type;
	public $hex;

	function __construct($id) {

		if ($id) {

			$row = DB::row('* FROM items_colors WHERE ID = ?', [$id]);

			$this->id = $id;
			$this->name = $row['name_it'];
			$this->slug = $row['tag'];
			$this->hex = $row['color'];
		}
	}
}
