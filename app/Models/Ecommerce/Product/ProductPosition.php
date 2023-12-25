<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductPosition extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items_positions';
	protected $fillable = [];
}
