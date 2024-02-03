<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model {

	protected $primaryKey = 'itemID';
	protected $table = 'items_prices';
	protected $fillable = [];
}
