<?php

namespace Ecommerce\Product;

use DatabaseObject;
use DBOld;

class Color {

	use DatabaseObject;

	public const TABLE = 'items_colors';
	public const INDEX = 'ID';

	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getName() {
		return $this->name_it;
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
