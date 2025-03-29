<?php

namespace App\Models\Ecommerce\Design;

use App\Models\Ecommerce\Design;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPhoto extends Model {


	protected $table = 'home_pics';
	protected $fillable = [];

	/**
	 * * Relationship methods
	 */
	// Get the design associated with the customer photo
	public function design(): BelongsTo {
		return $this->belongsTo(Design::class);
	}

	/**
	 * * Utility methods
	 */
	// Get the shown name of the customer
	public function fullName(): string {
		$firstName = $this->first_name;
		$lastName = $this->last_name || $this->last_name == '-' ? '' : substr($this->last_name, 0, 1) . '.';

		return implode(' ', [$firstName, $lastName]);
	}

	// Get the image path
	public function image(): string {
		return public_asset('users/testimonials/' . $this->id . '.jpg');
	}
}
