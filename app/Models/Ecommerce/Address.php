<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'addresses';
	protected $fillable = ['cartID', 'buyer', 'aname', 'address', 'number', 'city', 'zip', 'country', 'email', 'phone'];
	/**
	 * Get the address' cart
	 */
	public function cart_old(): BelongsTo {
		return $this->belongsTo(Cart::class, 'cartID', 'ID');
	}
}
