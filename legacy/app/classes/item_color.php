<?php

class Color {

	public $id = 0;
	public $slug = 'emp';
	public $name = '';
	public $type;
	public $hex;

	function __construct($id) {

		if ($id) {

			$row = \Legacy\System\Classes\LegacyDB::row('* FROM items_colors WHERE id = ?', [$id]);

			$this->id = $id;
			$this->name = $row['name'];
			$this->slug = $row['tag'];
			$this->hex = $row['color'];
		}
	}
}
