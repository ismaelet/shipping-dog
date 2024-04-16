<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model {


	protected $table = 'addresses';
	protected $fillable = ['cart_id', 'user_id', 'full_name', 'address', 'number', 'city', 'zip', 'country', 'email', 'phone'];
	/**
	 * Get the address' cart
	 */
	public function cart(): BelongsTo {
		return $this->belongsTo(Cart::class, 'cart_id', 'id');
	}
}
