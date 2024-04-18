<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model {

	protected $fillable = ['cart_id', 'user_id', 'full_name', 'address', 'number', 'city', 'zip', 'region', 'country', 'email', 'phone'];

	/*****************************
	 * * Relationship instances *
	 *****************************/
	/**
	 * Get the address' cart
	 */
	public function cart(): BelongsTo {
		return $this->belongsTo(Cart::class, 'cart_id', 'id');
	}

	/**************************
	 * * Utility methods *
	 **************************/
	/**
	 * Get the full address
	 */
	public function fullAddress(): string {
		$nameLine = $this->full_name;
		$addressLine = $this->address;
		$addressLine = $this->number ? $addressLine . ', ' . $this->number : $addressLine;
		$cityLine = $this->zip . ' ' . $this->city;
		$cityLine = $this->region ? $cityLine . ' (' . $this->region . ')' : $cityLine;

		return implode('<br>', array_filter([$nameLine, $addressLine, $cityLine, $this->country]));
	}
}
