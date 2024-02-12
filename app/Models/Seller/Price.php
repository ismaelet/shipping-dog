<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;

class Price extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'prices_stores';
	protected $fillable = [];
}
