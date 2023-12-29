<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Change extends Model {

	protected $table = 'ecommerce_changes';
	protected $fillable = ['order_id', 'change_request_id', 'is_change'];

	/**
	 * Get the order that owns the change.
	 */
	public function order() {
		return $this->belongsTo(Order::class, 'order_id', 'ID');
	}
}
