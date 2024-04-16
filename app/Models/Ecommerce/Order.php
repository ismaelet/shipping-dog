<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use Ecommerce\Order as EcommerceOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model {


	protected $table = 'orders';
	protected $fillable = ['fullProductPrice', 'price_disc', 'productGain', 'gain_disc', 'credit'];

	/**
	 * Get the order's product
	 */
	public function product(): HasOne {
		return $this->hasOne(Product::class, 'id', 'product_id');
	}

	/**
	 * Get the order's color
	 */
	public function color(): HasOne {
		return $this->hasOne(ProductColor::class, 'id', 'color_id');
	}

	/**
	 * Get the order's size
	 */
	public function size(): HasOne {
		return $this->hasOne(ProductSize::class, 'id', 'sizeID');
	}

	/**
	 * Get the order's position
	 */
	public function position(): HasOne {
		return $this->hasOne(ProductPosition::class, 'id', 'position_id');
	}

	/**
	 * Get the order's logo
	 */
	public function logo(): HasOne {
		return $this->hasOne(Logo::class, 'id', 'logo_id');
	}

	/**
	 * Returns the description of the order.
	 */
	public function description(): string {
		$product = $this->product;

		$description = $product->type->name;
		$description .= $this->size && strpos($product->type->sizes, ',') ? ' ' . $this->size->name : '';
		$description .= $this->color && strpos($product->type->colors, ',') ? ' ' . $this->color->name : '';
		$description .= $this->logo ? ' ' . $this->logo->name : '';
		$description .= $this->position_id && $this->position_id > 1 ? ' ' . $this->position->name : '';

		return $description;
	}

	/**
	 * ! USES OLD CLASS
	 * Returns the image of the order.
	 */
	public function clientImage() {
		$oldOrder = new EcommerceOrder($this->id);

		$image = $oldOrder->product->clientImage;

		return $image;
	}
}
