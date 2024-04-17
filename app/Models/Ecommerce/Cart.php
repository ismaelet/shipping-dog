<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use function Ecommerce\ecommerce_config;

class Cart extends Model {


	protected $table = 'carts';
	protected $fillable = ['user_id', 'has_errors'];


	// * Relationship instances * //
	/**
	 * Get the cart's shipping method
	 */
	public function shipping_method(): BelongsTo {
		return $this->belongsTo(ShippingMethod::class);
	}

	/**
	 * Get the cart's address
	 */
	public function address(): HasOne {
		return $this->hasOne(Address::class);
	}

	/**
	 * Get the cart coupon
	 */
	public function coupon(): HasOne {
		return $this->hasOne(Coupon::class, 'code', 'code');
	}

	/**
	 * Get the change request that generated this cart
	 */
	public function change_request(): HasOne {
		return $this->HasOne(ChangeRequest::class, 'new_cart_id');
	}

	/**
	 * Get the orders in the cart
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class);
	}

	// * Advanced data * //
	/**
	 * Generate the id code
	 */
	public function idCode(): string {
		return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
	}

	// * Collections * //
	/**
	 * Get the unlabelled carts
	 */
	public static function unlabelled(): Collection {
		return self::where('status', 1)
			->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
			->get();
	}

	// ! Deprecated ! //
	public function statusCode(): string {
		return ecommerce_config('cart-statuses')[$this->status][0];
	}

	public function statusName() {
		return ecommerce_config('cart-statuses')[$this->status][1];
	}
}
