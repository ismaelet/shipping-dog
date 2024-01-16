<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model {

	protected $table = 'inventory';
	protected $fillable = ['designId', 'typeId', 'colorId', 'sizeId', 'positionId', 'logoId'];
}
