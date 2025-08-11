<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warning extends Model {

	/**
	 * The mass assignable attributes
	 */
	protected $fillable = [
		'message',
		'start_date',
		'end_date',
	];

	/**
	 * Get the current active warning
	 */
	public static function currentWarning(): ?self {
		return self::where('start_date', '<=', now())
			->where('end_date', '>=', now())
			->orderByDesc('id')
			->first();
	}
}
