<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductType;
use App\Services\Ecommerce\OrderItemAmountCalculator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model {

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'unit_amount_before_discount',
        'total_amount_before_discount',
        'unit_discount',
        'total_discount',
        'unit_amount_after_discount',
        'total_amount_after_discount',
        'unit_amount_to_pay',
        'total_amount_to_pay',
        'unit_seller_earnings_before_reduction',
        'total_seller_earnings_before_reduction',
        'unit_seller_earnings_after_reduction',
        'total_seller_earnings_after_reduction',
        'unit_seller_reduction',
        'total_seller_reduction',
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
     * Get the the name of the order, including the design's name and id
     */
    protected function fullName(): Attribute {
        return Attribute::make(
            get: fn(): string => $this->quantity . 'x ' . ($this->design_id ? $this->design_id . ' - ' : '') . $this->product_with_trashed?->name . ' (' . $this->description() . ')'
        );
    }

    /**
     * Get the order that the order item belongs to
     */
    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order's seller
     */
    public function seller(): BelongsTo {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get the order's design
     */
    public function design(): BelongsTo {
        return $this->belongsTo(Design::class);
    }

    /**
     * Get the order's design including deleted designs
     */
    public function design_with_trashed(): BelongsTo {
        return $this->design()->withTrashed();
    }

    /**
     * Get the order's product
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order's product including trashed products
     */
    public function product_with_trashed(): BelongsTo {
        return $this->product()->withTrashed();
    }

    /**
     * Get the order's type
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
     * Get the description of the order
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
     * Get the full description of the order
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
     * Returns the image of the order
     */
    public function image(): string {
        return $this->product_with_trashed ? $this->product_with_trashed->image(
            color: $this->color,
            position: $this->position,
        ) : public_asset(PreviewHelper::PLACEHOLDER_IMAGE);
    }

    /**
     * Get the creation date of the order as a string
     */
    public function createdAtString(): string {
        return date('d/m/Y', strtotime($this->created_at));
    }

    /**
     * Get the used credit for the order item
     */
    public function usedCredit(): float {
        return $this->credit ? $this->total_amount_after_discount : 0;
    }

    /**
     * Check if the order has a discount
     */
    public function hasDiscount(): bool {
        return $this->unit_discount > 0 || $this->total_discount > 0;
    }

    /**
     * Check if the order is free (total amount to pay is 0 or less)
     */
    public function isFree(): bool {
        return $this->total_amount_to_pay <= 0;
    }

    /**
     * Update the prices of the order item
     * 
     * ! Right not this only depends on if the user is the owner of the product,
     * ! but in the future it should depend on the coupon applied to the order or general promotions
     */
    public function updatePrices(): self {
        $amounts = app(OrderItemAmountCalculator::class)->calculate(
            product: $this->product,
            color: $this->color,
            quantity: $this->quantity,
            credit: (bool) $this->credit,
        );

        $this->update($amounts);

        return $this;
    }

    /**
     * Scope a query to only include orders that have been paid
     */
    public static function hasBeenPaid(): Builder {
        return self::where('status', '>=', EcommerceHelper::status('confirmed'));
    }
}
