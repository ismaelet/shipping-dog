<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class BasePrice extends Model
{
    /**
     * Property: the primary key for the model
     */
    protected $primaryKey = 'type_id';

    /**
     * Property: the table associated with the model
     */
    protected $table = 'prices_default';

    /**
     * Property: the attributes that are mass assignable
     */
    protected $fillable = [
        'type_id',
        'price',
        'gain_w',
        'gain_c',
    ];
}
