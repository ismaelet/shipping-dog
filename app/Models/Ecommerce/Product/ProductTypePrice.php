<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductTypePrice extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'type_id',
		'price',
		'gain_w',
		'gain_c'
	];

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'prices_default';
}
