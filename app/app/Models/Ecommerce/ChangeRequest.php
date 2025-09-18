<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChangeRequest extends Model {

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = [
		'old_order_id',
		'new_order_id',
		'step'
	];

	/**
	 * Get the old cart that owns the change request
	 */
	public function old_order(): BelongsTo {
		return $this->belongsTo(Order::class, 'old_order_id');
	}

	/**
	 * Get the new cart generated from the change request
	 */
	public function new_order(): BelongsTo {
		return $this->belongsTo(Order::class, 'new_order_id');
	}

	/**
	 * Get the order items for the change request
	 */
	public function order_items(): HasMany {
		return $this->hasMany(Change::class, 'change_request_id');
	}

	/**
	 * Get the only the changes that are a real change, for the change request
	 */
	public function items_to_change(): HasMany {
		return $this->hasMany(Change::class, 'change_request_id')->where('is_change', true);
	}

	/**
	 * Get the only the changes that are a refund, for the change request
	 */
	public function items_to_refund(): HasMany {
		return $this->hasMany(Change::class, 'change_request_id')->where('is_change', false);
	}

	/**
	 * Check if the change request is only a refund
	 */
	public function isRefundOnly(): bool {
		return !$this->items_to_change;
	}
}
