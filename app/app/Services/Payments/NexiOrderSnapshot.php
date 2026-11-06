<?php

namespace App\Services\Payments;

use App\Models\Ecommerce\Order;
use Illuminate\Support\Arr;

class NexiOrderSnapshot
{
    public function fingerprint(Order $order): string
    {
        // Hash raw persisted values, including all item prices and shipping details.
        // Never persist the address itself in the payment record.
        $ignore = ['created_at', 'updated_at', 'time'];
        $attributes = Arr::except($order->getRawOriginal(), [...$ignore, 'status', 'token']);
        $items = $order->order_items()->orderBy('id')->lockForUpdate()->get()->map(
            fn ($item) => Arr::except($item->getRawOriginal(), $ignore)
        )->all();
        $address = $order->address()->lockForUpdate()->first();

        return hash('sha256', json_encode([
            $attributes, $items, $address ? Arr::except($address->getRawOriginal(), $ignore) : null,
        ], JSON_THROW_ON_ERROR));
    }
}
