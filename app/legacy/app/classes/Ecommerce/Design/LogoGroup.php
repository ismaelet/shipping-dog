<?php

namespace Legacy\Classes\Ecommerce\Design;

use LegacyDB;
use \Legacy\System\Traits\DatabaseObject;

class LogoGroup {

	use \Legacy\System\Traits\DatabaseObject;

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
		return rows_to_objects(\Legacy\System\Classes\LegacyDB::select(Logo::INDEX . ' FROM ' . Logo::TABLE . ' WHERE ' . Logo::LOGO_GROUP_INDEX . ' = ?', [$this->id]), Logo::class);
	}
}
