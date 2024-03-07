<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model {

	protected $primaryKey = 'itemID';
	protected $table = 'items_info';
	protected $fillable = [];
}
