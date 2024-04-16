<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model {

	protected $table = 'inventory';
	protected $fillable = ['design_id', 'type_id', 'colorId', 'size_id', 'position_id', 'logo_id'];
}
