<?php

namespace App\Models;

use App\Models\Ecommerce\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NexiPayment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'paid_at',
        'return_token',
        'fulfillment_status',
        'fulfillment_started_at',
        'fulfilled_at',
        'fulfillment_error',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
            'fulfillment_started_at' => 'datetime',
            'fulfilled_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
