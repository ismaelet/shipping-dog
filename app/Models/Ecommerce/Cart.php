<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'carts';
	protected $fillable = ['has_errors'];

	/**
	 * Get the cart's orders
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class, 'cartID', 'ID');
	}

	/**
	 * Get the cart's address
	 */
	public function address(): HasOne {
		return $this->hasOne(Address::class, 'cartID', 'ID');
	}

	/**
	 * Get the change request that generated this cart
	 */
	public function change_request(): HasOne {
		return $this->HasOne(ChangeRequest::class, 'new_cart_id', 'ID');
	}
}
