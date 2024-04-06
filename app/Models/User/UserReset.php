<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReset extends Model {

	use HasFactory;

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = [
		'user_id',
		'security_code',
	];

	/**
	 * The attributes that should be hidden for serialization
	 */
	protected $hidden = [
		'security_code',
	];

	/**
	 * The attributes that should be cast
	 */
	protected $casts = [
		'security_code' => 'hashed',
	];
}
