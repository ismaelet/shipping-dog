<?php

namespace App\Models\Ecommerce\Seller;

use Illuminate\Database\Eloquent\Model;

class SellerPrice extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'prices_stores';

	/**
	 * The attributes that are mass assignable.
	 * 
	 * @var array
	 */
	protected $fillable = [
		'seller_id',
		'type_id',
		'price',
		'gain_w',
		'gain_c',
	];
}
