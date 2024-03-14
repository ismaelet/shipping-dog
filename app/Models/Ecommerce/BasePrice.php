<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class BasePrice extends Model {

	protected $primaryKey = 'typeID';
	protected $table = 'prices_default';
	protected $fillable = ['price', 'gain_w', 'gain_c'];
}
