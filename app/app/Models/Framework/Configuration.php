<?php

namespace App\Models\Framework;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model {

	/**
	 * The table associated with the model
	 */
	protected $table = 'config';

	/**
	 * The attributes that are mass assignable
	 */
	protected $fillable = ['value'];
}
