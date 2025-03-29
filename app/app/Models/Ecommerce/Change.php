<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Product\ProductSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Change extends Model {

	protected $table = 'ecommerce_changes';
	protected $fillable = ['old_order_id', 'new_order_id', 'change_request_id', 'is_change'];

	/**
	 * Get the old order that owns the change.
	 */
	public function old_order(): BelongsTo {
		return $this->belongsTo(OrderItem::class, 'old_order_id', 'id');
	}

	/**
	 * Get the new order generated from the change
	 */
	public function new_order(): BelongsTo {
		return $this->belongsTo(OrderItem::class, 'new_order_id', 'id');
	}

	/**
	 * Get the old size of the change.
	 */
	public function old_size(): HasOne {
		return $this->hasOne(ProductSize::class, 'id', 'old_size_id');
	}

	/**
	 * Get the new size of the change.
	 */
	public function new_size(): HasOne {
		return $this->hasOne(ProductSize::class, 'id', 'new_size_id');
	}
}
