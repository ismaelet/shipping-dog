<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Like extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'likes';
	protected $fillable = ['userID', 'desID'];
}
