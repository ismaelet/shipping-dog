<?php

class Item_Preview {

	public $size;
	public $pos_x;
	public $pos_y;

	function __construct($row) {

		$this->size = $row['size'];
		$this->pos_x = $row['pos_x'];
		$this->pos_y = $row['pos_y'];
	}
}