<?php

namespace App\Models\Ecommerce\Seller;

use Illuminate\Database\Eloquent\Model;

class SellerPrice extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'prices_stores';
	protected $fillable = [];
}
