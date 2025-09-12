<?php

namespace App\Legacy\Ecommerce\Design;

use \Legacy\System\Traits\DatabaseObject;

class Logo {

	use DatabaseObject;

	public const TABLE = 'extras';
	public const INDEX = 'id';
	public const LOGO_GROUP_INDEX = 'logo_group_id';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getName() {
		return $this->name;
	}

	private function getImage() {
		$image = '/uploads/shops/logos/' . $this->id;

		if (!file_exists(public_server_path($image))) {
			$image .= '.png';
		}

		return $image;
	}

	private function getClientImage() {
		return public_client_path($this->image);
	}
}
