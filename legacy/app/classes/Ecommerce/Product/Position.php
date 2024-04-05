<?php

namespace Ecommerce\Product;

use DBOld;

class Position {

	private $data = [];

	public $id = null;
	public $name;
	public $info;
	public $editable;
	public $imagePositionId;

	function __construct($id) {
		if ($row = DBOld::row('* FROM items_positions WHERE ID = ?', [$id])) {
			$this->id = $id;
			$this->name = $row['name_' . 'it'];
			$this->info = $row['info'];
			$this->editable = $row['editable'];
			$this->imagePositionId = $row['base_of'] ?: $this->id;
		}
	}

	function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		} else {
			switch ($name) {
				case 'hasSpecificMockup':
					return $this->data['hasSpecificMockup'] = $this->imagePositionId == $this->id;
			}
		}
	}
}
