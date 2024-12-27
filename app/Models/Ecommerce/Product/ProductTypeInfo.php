<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTypeInfo extends Model {

	/**
	 * The attributes that are mass assignable.
	 * 
	 * @var array<int, string>
	 */
	protected $fillable = [
		'product_type_id',
		'description',
		'details'
	];

	/**
	 * The product type this info belongs to
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function type(): BelongsTo {
		return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
	}
}
