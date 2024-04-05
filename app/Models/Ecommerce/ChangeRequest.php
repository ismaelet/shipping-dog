<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChangeRequest extends Model {

	protected $table = 'ecommerce_change_requests';
	protected $fillable = ['old_cart_id', 'new_cart_id', 'step'];

	/**
	 * Get the old cart that owns the change request.
	 */
	public function old_cart_old(): BelongsTo {
		return $this->belongsTo(Cart::class, 'old_cart_id');
	}

	/**
	 * Get the new cart generated from the change request.
	 */
	public function new_cart_old(): BelongsTo {
		return $this->belongsTo(Cart::class, 'new_cart_id');
	}

	/**
	 * Get the changes for the change request.
	 */
	public function changes(): HasMany {
		return $this->hasMany(Change::class, 'change_request_id');
	}

	/**
	 * Get the only the changes that are a real change, for the change request.
	 */
	public function realChanges(): HasMany {
		return $this->hasMany(Change::class, 'change_request_id')->where('is_change', true);
	}
}
