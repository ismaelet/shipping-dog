<?php

namespace Legacy\Classes\Ecommerce\Product;

use \Legacy\System\Traits\DatabaseObject;

class Color {

	use \Legacy\System\Traits\DatabaseObject;

	public const TABLE = 'items_colors';
	public const INDEX = 'id';

	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getName() {
		return $this->name;
	}

	private function getSlug() {
		return $this->tag ?: 'emp';
	}

	private function getHex() {
		return $this->color;
	}

	private function getSecondHex() {
		return $this->secondColor;
	}

	private function getBicolor() {
		return (bool) $this->secondHex;
	}
}
