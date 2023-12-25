<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items';
	protected $fillable = [];

	/**
	 * Get the product's type
	 */
	public function type(): HasOne {
		return $this->hasOne(ProductType::class, 'ID', 'typeID');
	}
}
