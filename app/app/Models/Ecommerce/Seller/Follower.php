<?php

namespace App\Models\Ecommerce\Seller;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model {

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'follower_id'
    ];
}
