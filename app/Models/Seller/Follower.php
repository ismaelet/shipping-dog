<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Follower extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'followers';
	protected $fillable = ['userID', 'followID'];
}
