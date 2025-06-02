<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'website',
		'facebook',
		'instagram',
		'twitter',
		'youtube',
		'tiktok',
		'bluesky',
	];
}
