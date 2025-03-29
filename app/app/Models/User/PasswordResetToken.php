<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model {

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = [
		'email',
		'token',
	];
}
