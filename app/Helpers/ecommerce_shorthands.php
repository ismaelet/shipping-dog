<?php

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;

/**
 * Get the ID of the active cart.
 *
 * @return int|null The ID of the active cart, or null if no active cart exists
 */
function order_id(): ?int {
	return EcommerceHelper::cartId();
}

/**
 * Get the active cart.
 *
 * @return \App\Models\Ecommerce\Order|null The active cart object, or null if no active cart exists.
 */
function cart(): ?Order {
	return EcommerceHelper::cart();
}

/**
 * Get the number of items in the cart.
 *
 * @return int The number of items in the cart
 */
function cart_quantity(): int {
	return EcommerceHelper::cartQuantity();
}
