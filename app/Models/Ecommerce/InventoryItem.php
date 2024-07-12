<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model {

	protected $table = 'inventory';
	protected $fillable = [
		'product_id',
		'design_id',
		'type_id',
		'color_id',
		'size_id',
		'position_id',
		'logo_id'
	];

	/**
	 * * Relationships
	 */

	/**
	 * Get the product that owns the inventory item.
	 */
	public function product() {
		return $this->belongsTo(Product::class);
	}

	/**
	 * Get the design that owns the inventory item.
	 */
	public function design() {
		return $this->belongsTo(Design::class);
	}

	/**
	 * Get the type that owns the inventory item.
	 */
	public function type() {
		return $this->belongsTo(ProductType::class);
	}

	/**
	 * Get the color that owns the inventory item.
	 */
	public function color() {
		return $this->belongsTo(ProductColor::class);
	}

	/**
	 * Get the size that owns the inventory item.
	 */
	public function size() {
		return $this->belongsTo(ProductSize::class);
	}

	/**
	 * Get the position that owns the inventory item.
	 */
	public function position() {
		return $this->belongsTo(ProductPosition::class);
	}

	/**
	 * Get the logo that owns the inventory item.
	 */
	public function logo() {
		return $this->belongsTo(Logo::class);
	}
}
