<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductPosition extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items_positions';
	protected $fillable = [];

	/**
	 * Check if the position has a specific mockup
	 */
	public function hasSpecificMockup(): bool {
		return !$this->base_of;
	}

	/**
	 * Get the basic position ID
	 */
	public function basicPositionId(): int {
		return $this->base_of ?: $this->ID;
	}
}
