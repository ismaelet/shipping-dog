<?php

namespace App\Models\User;

use App\Models\Ecommerce\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model {

    /**
     * Property: The attributes that are mass assignable
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'color_id',
        'position_id',
    ];

    /**
     * Relationship: The product that is liked
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
