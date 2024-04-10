<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Admin section class
 */
class AdminSection extends Model {

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = ['category_id', 'name', 'slug', 'order', 'hidden', 'for_supervisors'];

	/**
	 * Get the category
	 */
	public function category(): BelongsTo {
		return $this->belongsTo(AdminCategory::class);
	}
}
