<?php

namespace App\Models\Ecommerce\Shop;

use Illuminate\Database\Eloquent\Model;

/**
 * Shop payment class
 * 
 */
class Payment extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'stores_payments';
	protected $fillable = [];
}
