<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items';
	protected $fillable = ['status'];

	/**
	 * Get the product's type
	 */
	public function type(): HasOne {
		return $this->hasOne(ProductType::class, 'ID', 'typeID');
	}

	/**
	 * Get the product's type
	 */
	public function price(): HasOne {
		return $this->hasOne(ProductPrice::class, 'itemID', 'ID');
	}

	/**
	 * Delete the product and clean the related data
	 */
	public function deleteAndClean($permanently = false) {
		if ($permanently) {
			$this->price()->delete();
			$this->delete();
		} else {
			$this->status = 0;
			$this->save();
		}
	}
}
