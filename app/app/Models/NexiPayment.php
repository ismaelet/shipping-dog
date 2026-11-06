<?php

namespace App\Models;

use App\Models\Ecommerce\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NexiPayment extends Model
{
    protected $hidden = ['hosted_page_url', 'return_token', 'security_token'];

    protected $fillable = [
        'order_fingerprint',
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
        'provider_order_id',
        'hosted_page_url',
        'security_token',
        'initialization_status',
        'initialized_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
            'fulfillment_started_at' => 'datetime',
            'fulfilled_at' => 'datetime',
            'initialized_at' => 'datetime',
            'security_token' => 'encrypted',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
