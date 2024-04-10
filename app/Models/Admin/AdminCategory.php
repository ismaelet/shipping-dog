<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Admin category class
 */
class AdminCategory extends Model {

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = ['name', 'slug', 'order', 'for_supervisors'];
}
