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

	/**
	 * Get the formatted description.
	 * 
	 * @return string
	 */
	public function formattedDescription(): ?string {
		return $this->description ? implode(
			'<br>',
			array_map(function ($element) {
				return "&middot; $element";
			}, preg_split('/\r\n|\r|\n/', $this->description)),
		) : null;
	}

	/**
	 * Get the formatted details.
	 * 
	 * @return string
	 */
	public function formattedDetails(): ?string {
		return $this->details ? implode(
			'<br>',
			array_map(function ($element) {
				return "&middot; $element";
			}, preg_split('/\r\n|\r|\n/', $this->details)),
		) : null;
	}
}
