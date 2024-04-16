<?php

namespace App\Models\Ecommerce\Design;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $primaryKey = 'design_id';
	protected $table = 'designs_tags';
	protected $fillable = [];
}
