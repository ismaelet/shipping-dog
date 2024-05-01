<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents an order in the e-commerce system.
 */
class Order extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['fullProductPrice', 'price_disc', 'productGain', 'gain_disc', 'credit', 'inventory', 'quantity', 'time', 'user_id', 'cart_id', 'product_id', 'seller_id', 'design_id', 'type_id', 'color_id', 'size_id', 'gender_id', 'position_id', 'logo_id'];


	// * Relationships * //

	/**
	 * Get the cart that the order belongs to.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function cart(): BelongsTo {
		return $this->belongsTo(Cart::class);
	}

	/**
	 * Get the order's seller.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class);
	}

	/**
	 * Get the order's product.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function product(): BelongsTo {
		return $this->belongsTo(Product::class);
	}

	/**
	 * Get the order's color.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function color(): BelongsTo {
		return $this->belongsTo(ProductColor::class);
	}

	/**
	 * Get the order's size.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function size(): BelongsTo {
		return $this->belongsTo(ProductSize::class);
	}

	/**
	 * Get the order's position.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function position(): BelongsTo {
		return $this->belongsTo(ProductPosition::class);
	}

	/**
	 * Get the order's logo.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function logo(): BelongsTo {
		return $this->belongsTo(Logo::class);
	}


	// * Utility methods * //

	/**
	 * Get the name of the order.
	 *
	 * @return string
	 */
	public function name(): string {
		$name = $this->quantity > 1 ? [$this->quantity . 'x'] : [];
		$name[] = $this->product && $this->product->status && !$this->product->deleted ? $this->product->name : 'Articolo rimosso';

		return implode(' ', $name);
	}

	/**
	 * Get the the name of the order, including the design's name and id.
	 *
	 * @return string
	 */
	public function fullName(): string {
		return ($this->design_id ? $this->design_id . ' - ' : '') . $this->name() . ' (' . $this->description() . ')';
	}

	/**
	 * Get the description of the order.
	 *
	 * @return string
	 */
	public function description(): string {
		$product = $this->product;

		$description = $product?->type->name;
		$description .= $this->size && strpos($product?->type->sizes, ',') ? ' ' . $this->size->name : '';
		$description .= $this->color && strpos($product?->type->colors, ',') ? ' ' . $this->color->name : '';
		$description .= $this->logo ? ' ' . $this->logo->name : '';
		$description .= $this->position_id && $this->position_id > 1 ? ' ' . $this->position->name : '';

		return $description;
	}

	/**
	 * Get the full description of the order.
	 *
	 * @return string
	 */
	public function multilineDescription(): string {
		if (!$this->product || !$this->product->status || $this->product->deleted) {
			return 'Articolo rimosso';
		}

		$type = $this->product->type->name;
		$size = $this->size_id ? 'Taglia: ' . $this->size->name : null;
		$color = $this->color_id ? 'Colore: ' . $this->color->name : null;
		$position = $this->position_id > 1 ? $this->position->name : null;
		$logo = $this->logo_id ? $this->logo->name : null;

		return implode('<br>', array_filter([$type, $size, $color, $position, $logo]));
	}

	/**
	 * Returns the image of the order.
	 *
	 * @return string
	 */
	public function image(): string {
		return $this->product->image(null, $this->color_id, $this->position_id);
	}

	/**
	 * Get the creation date of the order as a string.
	 *
	 * @return string
	 */
	public function createdAtString(): string {
		return date('d/m/Y', strtotime($this->time));
	}


	// ~ Price ~ //

	/**
	 * Get the gross price of a single product.
	 *
	 * @return float
	 */
	public function grossProductPrice(): float {
		return $this->fullProductPrice;
	}

	/**
	 * Get the discount of a single product.
	 *
	 * @return float
	 */
	public function productDiscount(): float {
		return $this->price_disc;
	}

	/**
	 * Get the price of a single product.
	 *
	 * @return float
	 */
	public function productPrice(): float {
		return $this->grossProductPrice() - $this->productDiscount();
	}

	/**
	 * Get the total gross price of the order.
	 *
	 * @return float
	 */
	public function grossPrice(): float {
		return $this->grossProductPrice() * $this->quantity;
	}

	/**
	 * Get the total discount.
	 *
	 * @return float
	 */
	public function discount(): float {
		return $this->credit ? $this->grossPrice() : $this->productDiscount() * $this->quantity;
	}

	/**
	 * Get the total price of the order.
	 *
	 * @return float
	 */
	public function price(): float {
		return $this->grossPrice() - $this->discount();
	}

	/**
	 * Get the seller's total gain for the order.
	 *
	 * @return float
	 */
	public function totalGain(): float {
		return ($this->productGain - $this->gain_disc) * $this->quantity;
	}

	/**
	 * Get the price of the order as a formatted string.
	 *
	 * @return string
	 */
	public function priceString(): string {
		return EcommerceHelper::formatPrice($this->price());
	}

	/**
	 * Get the gross price of the order as a formatted string.
	 *
	 * @return string
	 */
	public function grossPriceString(): string {
		return EcommerceHelper::formatPrice($this->grossPrice());
	}

	/**
	 * Get the seller's total gain for the order as a formatted string.
	 *
	 * @return string
	 */
	public function totalGainString(): string {
		return EcommerceHelper::formatPrice($this->totalGain(), true);
	}


	// * Actions * //

	/**
	 * Deletes the order.
	 *
	 * @return bool|null
	 */
	public function delete(): bool|null {
		$result = parent::delete();
		$cart = $this->cart;

		if ($cart->orders->isEmpty()) {
			$cart->delete();
		}

		return $result;
	}
}
