<?php

namespace App\Models\User;

use App\Models\Ecommerce\Design;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a like of a user for a design.
 */
class Like extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'likes';
	protected $fillable = ['user_id', 'design_id'];

	/**
	 * * Relationships
	 */

	/**
	 * Get the design associated with the like.
	 */
	public function design(): BelongsTo {
		return $this->belongsTo(Design::class);
	}
}
