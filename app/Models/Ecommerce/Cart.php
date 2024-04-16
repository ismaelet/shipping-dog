<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model {


	protected $table = 'carts';
	protected $fillable = ['user_id', 'has_errors'];

	/**
	 * Get the cart's orders
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class, 'cart_id', 'id');
	}

	/**
	 * Get the cart's address
	 */
	public function address(): HasOne {
		return $this->hasOne(Address::class, 'cart_id', 'id');
	}

	/**
	 * Get the change request that generated this cart
	 */
	public function change_request(): HasOne {
		return $this->HasOne(ChangeRequest::class, 'new_cart_id', 'id');
	}

	/**
	 * Get the unlabelled carts
	 */
	public static function unlabelled(): Collection {
		return self::where('status', 1)
			->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
			->get();
	}
}
