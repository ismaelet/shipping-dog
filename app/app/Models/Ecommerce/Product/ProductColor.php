<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductColor extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'product_colors';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'tag',
		'primary_color_hex',
		'secondary_color_hex',
		'name',
		'type',
		'dark',
		'reorder',
		'image',
	];

	/**
	 * Get the colors associated with the product type.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function types(): BelongsToMany {
		return $this->belongsToMany(ProductType::class);
	}
}
