<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductGender extends Model
{
    protected $table = 'items_genders';

    protected $fillable = ['tag', 'name'];
}
