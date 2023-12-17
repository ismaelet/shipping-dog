<?php

namespace Ecommerce\Design;

use DB;
use DatabaseObject;

class LogoGroup {

	use DatabaseObject;

	public const TABLE = 'extras_folder';
	public const INDEX = 'ID';
	public const SELLER_INDEX = 'storID';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry ? $this->databaseEntry[self::INDEX] : null;
	}

	private function getName() {
		return $this->name_it;
	}

	private function getLogos() {
		return rows_to_objects(DB::select(Logo::INDEX . ' FROM ' . Logo::TABLE . ' WHERE ' . Logo::LOGO_GROUP_INDEX . ' = ?', [$this->id]), Logo::class);
	}
}
