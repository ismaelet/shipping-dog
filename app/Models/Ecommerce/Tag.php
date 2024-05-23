<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $table = 'sys_tags';
	protected $fillable = [
		'tag', 'count', 'sales', 'hide'
	];
}
