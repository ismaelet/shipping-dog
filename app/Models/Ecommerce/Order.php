<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'orders';
	protected $fillable = [];

	/**
	 * Get the order's product
	 */
	public function product(): HasOne {
		return $this->hasOne(Product::class, 'ID', 'itemID');
	}

	/**
	 * Get the order's color
	 */
	public function color(): HasOne {
		return $this->hasOne(ProductColor::class, 'ID', 'colorID');
	}

	/**
	 * Get the order's size
	 */
	public function size(): HasOne {
		return $this->hasOne(ProductSize::class, 'ID', 'sizeID');
	}

	/**
	 * Get the order's position
	 */
	public function position(): HasOne {
		return $this->hasOne(ProductPosition::class, 'ID', 'posID');
	}

	/**
	 * Get the order's logo
	 */
	public function logo(): HasOne {
		return $this->hasOne(Logo::class, 'ID', 'extID');
	}

	/**
	 * Returns the description of the order.
	 */
	public function description(): string {
		$product = $this->product;

		$description = $product->type->name;
		$description .= $this->size && strpos($product->type->sizes, ',') ? ' ' . $this->size->name : '';
		$description .= $this->color && strpos($product->type->colors, ',') ? ' ' . $this->color->name_it : '';
		$description .= $this->logo ? ' ' . $this->logo->name : '';
		$description .= $this->posID && $this->posID > 1 ? ' ' . $this->position->name_it : '';

		return $description;
	}
}
