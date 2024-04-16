<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTypeInfo extends Model {

	protected $fillable = ['product_type_id', 'description', 'details'];

	/**
	 * The product type this info belongs to
	 */
	public function type(): BelongsTo {
		return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
	}
}
