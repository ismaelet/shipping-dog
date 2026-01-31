<?php

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;

/**
 * Get the ID of the active cart
 */
function cart_id(): ?int {
    return EcommerceHelper::cartId();
}

/**
 * Get the active cart
 */
function cart(): ?Order {
    return EcommerceHelper::cart();
}

/**
 * Formats the given price value as a string
 */
function price(float $value, bool $forceNumber = true): string {
    return EcommerceHelper::formatPrice($value, $forceNumber);
}
