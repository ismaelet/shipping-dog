<?php

namespace Ecommerce\Design;

use DBOld;
use DatabaseObject;

class LogoGroup {

	use DatabaseObject;

	public const TABLE = 'extras_folder';
	public const INDEX = 'id';
	public const SELLER_INDEX = 'seller_id';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry ? $this->databaseEntry[self::INDEX] : null;
	}

	public function getName() {
		return $this->name;
	}

	private function getLogos() {
		return rows_to_objects(DBOld::select(Logo::INDEX . ' FROM ' . Logo::TABLE . ' WHERE ' . Logo::LOGO_GROUP_INDEX . ' = ?', [$this->id]), Logo::class);
	}
}
