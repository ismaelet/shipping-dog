<?php

namespace App\Helpers;

use NumberFormatter;

class EcommerceHelper {

    /**
     * Format a price value according to the current currency
     */
    public static function formatPrice(?float $value, bool $forceNumber = true): string {
        if (($value === null || $value === 0) && !$forceNumber) {
            return __('Free');
        }

        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($value ?? 0, config('custom.currency', 'EUR'));
    }

    /**
     * Get the status index based on the given status tag
     */
    public static function statusFromTag(string $tag): ?int {
        $statusKeyPosition = array_search($tag, array_column(config('ecommerce.order.statuses'), 'tag'));

        if ($statusKeyPosition === false) {
            return null;
        }

        return array_keys(config('ecommerce.order.statuses'))[$statusKeyPosition];
    }

    /**
     * Get the step index based on the given step tag
     */
    public static function stepFromTag(string $tag): ?int {
        $stepKeyPosition = array_search($tag, array_column(config('ecommerce.cart.steps'), 'tag'));

        if ($stepKeyPosition === false) {
            return null;
        }

        return array_keys(config('ecommerce.cart.steps'))[$stepKeyPosition];
    }
}
