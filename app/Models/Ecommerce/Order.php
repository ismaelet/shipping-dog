<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model {

	protected $table = 'orders';
	protected $fillable = ['fullProductPrice', 'price_disc', 'productGain', 'gain_disc', 'credit', 'inventory'];

	/****************************
	 * * Relationship instances *
	 ****************************/
	/**
	 * Get the order's cart
	 */
	public function cart(): BelongsTo {
		return $this->belongsTo(Cart::class);
	}

	/**
	 * Get the order's seller
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class);
	}

	/**
	 * Get the order's product
	 */
	public function product(): BelongsTo {
		return $this->belongsTo(Product::class);
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
		return $this->hasOne(ProductSize::class, 'id', 'size_id');
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
	/************************
	 * * Utility methods *
	 ************************/
	/**
	 * Get the name of the order
	 */
	public function name(): string {
		$name = $this->quantity > 1 ? [$this->quantity . 'x'] : [];
		$name[] = $this->product && $this->product->status && !$this->product->deleted ? $this->product->name : 'Articolo rimosso';

		return implode(' ', $name);
	}

	/**
	 * Get the name of the order, including the design name
	 */
	public function fullName(): string {
		return ($this->design_id ? $this->design_id . ' - ' : '') . $this->name() . ' (' . $this->description() . ')';
	}

	/**
	 * Get the description of the order
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

	public function condensedDescription(): string {
		if (!$this->product || !$this->product->status || $this->product->deleted) {
			return 'Articolo rimosso';
		}

		$type = $this->product->type->name;
		// $type = $this->product->type->sibling && !$this->product->type->unisex ? $type . ' - ' . $this->product->type->gender_model->name : $type;
		$size = $this->size_id ? 'Taglia: ' . $this->size->name : null;
		$color = $this->color_id ? 'Colore: ' . $this->color->name : null;
		$position = $this->position_id > 1 ? $this->position->name : null;
		$logo = $this->logo_id ? $this->logo->name : null;

		return implode('<br>', array_filter([$type, $size, $color, $position, $logo]));
	}

	/**
	 * Get the price
	 */
	public function grossProductPrice(): float {
		return $this->fullProductPrice;
	}

	public function productDiscount(): float {
		return $this->price_disc;
	}

	public function productPrice(): float {
		return $this->grossProductPrice() - $this->productDiscount();
	}

	public function grossPrice(): float {
		return $this->grossProductPrice() * $this->quantity;
	}

	public function discount(): float {
		return $this->credit ? $this->grossPrice() : $this->productDiscount() * $this->quantity;
	}

	public function price(): float {
		return $this->grossPrice() - $this->discount();
	}

	public function priceString(): string {
		return EcommerceHelper::formatPrice($this->price());
	}

	public function grossPriceString(): string {
		return EcommerceHelper::formatPrice($this->grossPrice());
	}

	/**
	 * Get the gain amount
	 */
	public function totalGain(): float {
		return ($this->productGain - $this->gain_disc) * $this->quantity;
	}

	public function totalGainString(): string {
		return EcommerceHelper::formatPrice($this->totalGain(), true);
	}

	/**
	 * Get the time string
	 */
	public function timeString(): string {
		return date('d/m/Y', strtotime($this->time));
	}

	/**
	 * Returns the image of the order
	 */
	public function image(): string {
		return $this->product->image(null, $this->color_id, $this->position_id);
	}

	// * Actions * //
	/**
	 * Deletes the order
	 */
	public function delete(): bool|null {
		$results = parent::delete();

		$this->cart->updateOrders();

		return $results;
	}
}
