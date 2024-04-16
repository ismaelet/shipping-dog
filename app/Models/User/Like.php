<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {


	protected $table = 'likes';
	protected $fillable = ['user_id', 'design_id'];
}
