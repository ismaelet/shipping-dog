<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductTypeMeasure extends Model {

	protected $table = 'items_types_measures';
	protected $fillable = ['typeId', 'size', 'val'];
}
