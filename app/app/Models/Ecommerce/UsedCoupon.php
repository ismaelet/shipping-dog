<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class UsedCoupon extends Model {

	protected $table = 'codes_used';
	protected $fillable = ['coupon_id', 'user_id'];
}
