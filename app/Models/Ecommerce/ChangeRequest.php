<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChangeRequest extends Model {

	protected $table = 'ecommerce_change_requests';
	protected $fillable = ['cart_id', 'step'];

	/**
	 * Get the cart that owns the change request.
	 */
	public function cart(): BelongsTo {
		return $this->belongsTo(Cart::class, 'cart_id');
	}

	/**
	 * Get the changes for the change request.
	 */
	public function changes(): HasMany {
		return $this->hasMany(Change::class, 'change_request_id');
	}
}
