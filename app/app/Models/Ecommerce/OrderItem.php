<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents an order in the e-commerce system.
 */
class OrderItem extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'fullProductPrice',
		'price_disc',
		'gain',
		'productGain',
		'gain_disc',
		'credit',
		'inventory',
		'quantity',
		'user_id',
		'order_id',
		'product_id',
		'seller_id',
		'design_id',
		'type_id',
		'color_id',
		'size_id',
		'gender_id',
		'position_id',
		'logo_id',
		'status',
		'is_printed',
		'is_in_cart',
		'created_at',
	];

	/**
	 * Get the the name of the order, including the design's name and id.
	 *
	 * @return Attribute
	 */
	protected function fullName(): Attribute {
		return Attribute::make(
			get: fn(): string => $this->quantity . 'x ' . ($this->design_id ? $this->design_id . ' - ' : '') . $this->product_with_trashed?->name . ' (' . $this->description() . ')'
		);
	}

	/**
	 * Get the order that the order item belongs to.
	 *
	 * @return BelongsTo
	 */
	public function order(): BelongsTo {
		return $this->belongsTo(Order::class);
	}

	/**
	 * Get the order's seller.
	 *
	 * @return BelongsTo
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class);
	}

	/**
	 * Get the order's design.
	 *
	 * @return BelongsTo
	 */
	public function design(): BelongsTo {
		return $this->belongsTo(Design::class);
	}

	/**
	 * Get the order's product.
	 *
	 * @return BelongsTo
	 */
	public function product(): BelongsTo {
		return $this->belongsTo(Product::class);
	}

	/**
	 * Get the order's product including trashed products.
	 *
	 * @return BelongsTo
	 */
	public function product_with_trashed(): BelongsTo {
		return $this->product()->withTrashed();
	}

	/**
	 * Get the order's type.
	 *
	 * @return BelongsTo
	 */
	public function type(): BelongsTo {
		return $this->belongsTo(ProductType::class, 'type_id');
	}

	/**
	 * Get the order's color.
	 *
	 * @return BelongsTo
	 */
	public function color(): BelongsTo {
		return $this->belongsTo(ProductColor::class);
	}

	/**
	 * Get the order's size.
	 *
	 * @return BelongsTo
	 */
	public function size(): BelongsTo {
		return $this->belongsTo(ProductSize::class);
	}

	/**
	 * Get the order's position.
	 *
	 * @return BelongsTo
	 */
	public function position(): BelongsTo {
		return $this->belongsTo(ProductPosition::class);
	}

	/**
	 * Get the order's logo.
	 *
	 * @return BelongsTo
	 */
	public function logo(): BelongsTo {
		return $this->belongsTo(Logo::class);
	}

	/**
	 * Get the order's code.
	 *
	 * @return string
	 */
	public function idCode(): string {
		return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
	}

	/**
	 * Get the name of the order.
	 *
	 * @return string
	 */
	public function name(): string {
		$name = $this->quantity > 1 ? [$this->quantity . 'x'] : [];
		$name[] = $this->product_with_trashed->name ?? '';
		$name[] = !$this->product_with_trashed || $this->product_with_trashed->trashed() ? '-articolo rimosso-' :  null;

		return implode(' ', array_filter($name));
	}

	/**
	 * Get the description of the order.
	 *
	 * @return string
	 */
	public function description(): string {
		$description = $this->type?->name;
		$description .= $this->color && $this->type?->colors->count() > 1 ? ' ' . $this->color?->name : '';
		$description .= $this->size && $this->type?->sizes->count() > 1 ? ' ' . $this->size?->name : '';
		$description .= $this->logo ? ' ' . $this->logo?->name : '';
		$description .= $this->position_id && $this->position_id > 1 ? ' ' . $this->position?->name : '';

		return $description;
	}

	/**
	 * Get the full description of the order.
	 *
	 * @return string
	 */
	public function multilineDescription(): string {
		if (!$this->product_with_trashed) {
			return 'Articolo rimosso';
		}

		$type = $this->product_with_trashed->type->name;
		$size = $this->size_id ? 'Taglia: ' . $this->size->name : null;
		$color = $this->color ? 'Colore: ' . $this->color->name : null;
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
		return $this->product_with_trashed ? $this->product_with_trashed->image(
			color: $this->color,
			position: $this->position,
		) : public_asset(PreviewHelper::PLACEHOLDER_IMAGE);
	}

	/**
	 * Get the creation date of the order as a string.
	 *
	 * @return string
	 */
	public function createdAtString(): string {
		return date('d/m/Y', strtotime($this->created_at));
	}

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
	 * Get the total price of the order before applying credit (that would bring it to 0).
	 *
	 * @return float
	 */
	public function priceBeforeCredit(): float {
		return $this->productPrice() * $this->quantity;
	}

	/**
	 * Get the total discount.
	 *
	 * @return float
	 */
	public function discount(): float {
		return $this->credit ? $this->grossProductPrice() : $this->productDiscount() * $this->quantity;
	}

	/**
	 * Get the used credit for the order item.
	 * 
	 * @return float
	 */
	public function usedCredit(): float {
		return $this->credit ? $this->priceBeforeCredit() : 0;
	}

	/**
	 * Get the total price of the order.
	 *
	 * @return float
	 */
	public function price(): float {
		return $this->credit ? 0 : round($this->grossPrice() - $this->discount(), 2);
	}

	/**
	 * The total show price of the order.
	 *
	 * @return float
	 */
	public function shownPrice(): float {
		return $this->priceBeforeCredit();
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
	public function gainString(): string {
		return EcommerceHelper::formatPrice($this->gain, true);
	}

	/**
	 * Updates the gain of the order.
	 */
	public function updateGain(): void {
		$this->gain = ($this->productGain * $this->quantity) - $this->gain_disc;
		$this->save();
	}

	/**
	 * Deletes the order.
	 *
	 * @return bool|null
	 */
	public function delete(): bool|null {
		$result = parent::delete();
		$order = $this->order;

		if ($order->order_items->isEmpty()) {
			$order->delete();
		} else {
			$order->updateOrderItems();
		}

		return $result;
	}

	/**
	 * Scope a query to only include orders that have been paid.
	 *
	 * @return Builder
	 */
	public static function hasBeenPaid(): Builder {
		return self::where('status', '>=', EcommerceHelper::statusFromTag('confirmed'));
	}
}
