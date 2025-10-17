<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model {

    /**
     * Property: The primary key associated with the table
     */
    protected $primaryKey = 'product_id';

    /**
     * Property: The table associated with the model
     */
    protected $table = 'items_info';

    /**
     * Property: The attributes that are mass assignable
     */
    protected $fillable = [
        'product_id',
        'description',
    ];
}
