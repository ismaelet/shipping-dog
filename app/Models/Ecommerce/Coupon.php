<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {

	protected $table = 'codes';
	protected $fillable = [
		'code',
		'mod_us',
		'mod_them',
		'flat_us',
		'flat_them',
		'permanent',
		'shipping',
		'only_one',
		'one_per_acccount',
		'time_start',
		'time_end',
	];
}
