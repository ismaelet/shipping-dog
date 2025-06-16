<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model {

	protected $primaryKey = 'product_id';

	protected $table = 'items_prices';

	protected $fillable = [
		'product_id',
		'price',
		'gain_w',
		'gain_c'
	];
}
