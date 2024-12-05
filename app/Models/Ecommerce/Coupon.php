<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'codes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
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

	/**
	 * Get the seller on whose products the coupon can be used.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class);
	}
}
