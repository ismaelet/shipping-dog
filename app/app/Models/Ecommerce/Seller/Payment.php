<?php

namespace App\Models\Ecommerce\Seller;

use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seller_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_id',
        'value',
        'other',
        'description',
    ];

    /**
     * * Relationships
     */

    /**
     * Get the seller that received the payment.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
