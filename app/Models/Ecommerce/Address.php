<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model {

	protected $fillable = ['order_id', 'user_id', 'full_name', 'address', 'number', 'city', 'zip', 'region', 'country', 'email', 'phone', 'txnID'];

	/*****************************
	 * * Relationship instances *
	 *****************************/
	/**
	 * Get the address' cart
	 */
	public function cart(): BelongsTo {
		return $this->belongsTo(Order::class, 'order_id', 'id');
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

		return implode('<br>', array_filter([$nameLine, $addressLine, $cityLine]));
	}

	/**
	 * Get the customer's contacts
	 */
	public function contacts(): string {
		return implode('<br>', array_filter([$this->phone, $this->email]));
	}
}
