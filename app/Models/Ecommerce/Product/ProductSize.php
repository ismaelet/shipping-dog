<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductSize extends Model {

	protected $table = 'items_sizes';
	protected $fillable = [];

	/**
	 * * Relationships
	 */

	/**
	 * Get the product types for the size.
	 */
	public function types(): BelongsToMany {
		return $this->belongsToMany(ProductType::class);
	}
}
