<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model {

	protected $primaryKey = 'product_id';
	protected $table = 'items_info';
	protected $fillable = [
		'product_id',
		'description',
	];
}
