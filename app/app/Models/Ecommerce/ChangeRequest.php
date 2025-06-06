<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChangeRequest extends Model {

	/**
	 * The attributes that are mass assignable.
	 * 
	 * @var array<int, string>
	 */
	protected $fillable = [
		'old_order_id',
		'new_order_id',
		'step'
	];

	/**
	 * Get the old cart that owns the change request.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function old_order(): BelongsTo {
		return $this->belongsTo(Order::class, 'old_order_id');
	}

	/**
	 * Get the new cart generated from the change request.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function new_order(): BelongsTo {
		return $this->belongsTo(Order::class, 'new_order_id');
	}

	/**
	 * Get the changes for the change request.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function changes(): HasMany {
		return $this->hasMany(Change::class, 'change_request_id');
	}

	/**
	 * Get the only the changes that are a real change, for the change request.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function realChanges(): HasMany {
		return $this->hasMany(Change::class, 'change_request_id')->where('is_change', true);
	}
}
