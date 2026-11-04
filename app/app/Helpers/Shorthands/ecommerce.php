<?php

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Services\Ecommerce\CartResolver;

/**
 * Get the ID of the active cart
 */
function cart_id(): ?int
{
    return app(CartResolver::class)->cartId();
}

/**
 * Get the active cart
 */
function cart(): ?Order
{
    return app(CartResolver::class)->cart();
}

/**
 * Formats the given price value as a string
 */
function price(float $value, bool $forceNumber = true): string
{
    return EcommerceHelper::formatPrice($value, $forceNumber);
}
