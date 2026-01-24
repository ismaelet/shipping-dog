<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductSize extends Model {

    /**
     * Property: The table associated with the model
     */
    protected $table = 'items_sizes';

    /**
     * Property: The attributes that are mass assignable
     */
    protected $fillable = [];

    /**
     * Relationship: Get the product types for the size
     */
    public function types(): BelongsToMany {
        return $this->belongsToMany(ProductType::class);
    }
}
