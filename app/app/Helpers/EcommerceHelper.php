<?php

namespace App\Helpers;

use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use NumberFormatter;

class EcommerceHelper {

    /**
     * Get the ID of the active cart
     */
    public static function cartId(): ?int {
        // If the user is logged in, return the ID of their cart
        if (Auth::check()) {
            return Auth::user()->cart?->id;
        }

        // Otherwise, return the ID of the cart stored in the session
        $sessionCart = Order::where('id', session('cart_id'))
            ->where('status', [EcommerceHelper::status('open')])
            ->first();

        // Return the ID of the session cart if it exists, otherwise null
        return $sessionCart?->id;
    }

    /**
     * Get the active cart
     */
    public static function cart(): ?Order {
        return Order::find(self::cartId());
    }

    /**
     * Format a price value according to the current currency
     */
    public static function formatPrice(?float $value, bool $forceNumber = true): string {
        if (!$value && !$forceNumber) {
            return __('Free');
        }

        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);

        $formatted = $formatter->formatCurrency($value, config('custom.currency', 'EUR'));

        // Replace non-breaking space (&nbsp;) with a regular space
        return str_replace("\u{00A0}", ' ', $formatted);
    }

    /**
     * Get the step index based on the given step tag
     */
    public static function stepFromTag(string $stepTag): int {
        $stepKeyPosition = array_search($stepTag, array_column(config('ecommerce.cart.steps'), 'tag'));

        if ($stepKeyPosition === false) {
            return false;
        }

        return array_keys(config('ecommerce.cart.steps'))[$stepKeyPosition];
    }

    /**
     * Get the status index based on the given status tag
     */
    public static function status(string $statusTag): int {
        $statusKeyPosition = array_search($statusTag, array_column(config('ecommerce.order.statuses'), 'tag'));

        if ($statusKeyPosition === false) {
            return false;
        }

        return array_keys(config('ecommerce.order.statuses'))[$statusKeyPosition];
    }
}
