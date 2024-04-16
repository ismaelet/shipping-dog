<?php

namespace App\Models\Ecommerce\Design;

use Illuminate\Database\Eloquent\Model;

class LogoGroup extends Model {


	protected $table = 'extras_folder';
	protected $fillable = [];

	/**
	 * Get the logo group's logos
	 */
	public function logos() {
		return $this->hasMany(Logo::class, 'logo_group_id', 'id');
	}

	/**
	 * Delete the logo group and clean the related data
	 */
	public function deleteAndClean() {
		// Delete the logos
		$this->logos()->delete();

		// Delete the logo group
		$this->delete();
	}
}
