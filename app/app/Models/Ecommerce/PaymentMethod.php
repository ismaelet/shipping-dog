<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    public static function allowedFor(Order $order): Builder
    {
        $ids = (string) $order->price === '0.00' || (float) $order->price === 0.0
            ? [4]
            : ($order->shipping_method()->first()?->isLocalDelivery() ? [1, 2, 3] : [1, 2]);

        return static::where('active', 1)->whereIn('id', $ids);
    }
}
