<?php

namespace App\Models\Framework;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model {

	/**
	 * The table associated with the model.
	 * 
	 * @var string
	 */
	protected $table = 'config';



	/**
	 * The attributes that are mass assignable.
	 * 
	 * @var array<string>
	 */
	protected $fillable = ['value'];
}
