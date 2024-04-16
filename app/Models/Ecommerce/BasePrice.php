<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class BasePrice extends Model {

	protected $primaryKey = 'type_id';
	protected $table = 'prices_default';
	protected $fillable = ['type_id', 'price', 'gain_w', 'gain_c'];
}
