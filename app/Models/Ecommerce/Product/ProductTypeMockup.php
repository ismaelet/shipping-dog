<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductTypeMockup extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items_mockups';
	protected $fillable = ['typeID', 'positionID', 'pos_x_min', 'pos_x_def', 'pos_y_min', 'pos_y_def', 'siz_x_max', 'siz_x_def', 'siz_y_max'];
}
