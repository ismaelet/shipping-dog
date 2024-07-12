<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductPosition extends Model {


	protected $fillable = [];

	/**
	 * * Relationships
	 */

	/**
	 * Get the product types for the position.
	 */
	public function types(): BelongsToMany {
		return $this->belongsToMany(ProductType::class);
	}

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
		return $this->base_of ?: $this->id;
	}
}
