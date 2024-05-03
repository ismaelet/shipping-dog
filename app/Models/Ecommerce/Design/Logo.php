<?php

namespace App\Models\Ecommerce\Design;

use Illuminate\Database\Eloquent\Model;

class Logo extends Model {

	protected $fillable = [];

	/**
	 * Get the image path for the logo
	 */
	public function image(): string {
		if (file_exists(public_storage($url = 'shops/logos/' . $this->id . '.webp'))) {
			return public_asset($url);
		}

		return public_asset('shops/logos/' . $this->id . '.png');
	}
}
