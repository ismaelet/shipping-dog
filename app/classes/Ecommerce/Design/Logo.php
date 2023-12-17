<?php

namespace Ecommerce\Design;

use DatabaseObject;

class Logo {

	use DatabaseObject;

	public const TABLE = 'extras';
	public const INDEX = 'ID';
	public const LOGO_GROUP_INDEX = 'extFID';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getName() {
		return $this->name_it;
	}

	private function getImage() {
		$image = '/uploads/logos/' . $this->id;

		if (!file_exists(public_server_path($image))) {
			$image .= '.png';
		}

		return $image;
	}

	private function getClientImage() {
		return public_client_path($this->image);
	}
}
