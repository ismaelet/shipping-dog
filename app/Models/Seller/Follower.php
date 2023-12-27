<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'followers';
	protected $fillable = ['userID', 'followID'];
}
