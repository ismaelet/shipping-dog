<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {

	protected $table = 'codes';
	protected $fillable = [
		'code',
		'mod_us',
		'mod_them',
		'permanent',
		'shipping',
		'time_start',
		'time_end',
	];
}
