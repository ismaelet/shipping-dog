<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class UsedCoupon extends Model {

	/**
	 * The attributes that are mass assignable.
	 * 
	 * @var array<int:string>
	 */
	protected $fillable = [
		'coupon_id',
		'user_id'
	];
}
