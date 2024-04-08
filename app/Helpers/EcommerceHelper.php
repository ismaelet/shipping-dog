<?php

namespace App\Helpers;

use App\Models\Ecommerce\Cart;
use Illuminate\Support\Facades\Auth;

class EcommerceHelper {

	/**
	 * Get the ID of the active cart
	 */
	public static function cartId(): ?int {
		if (auth()->check() && auth()->user()->cart) {
			return auth()->user()->cart->ID;
		}

		return $_SESSION['cart_id'] ?? null;
	}

	/**
	 * Get the active cart
	 */
	public static function cart(): ?Cart {
		if (auth()->check()) {
			return Auth::user()->cart ?? null;
		}

		if (isset($_SESSION['cart_id'])) {
			return Cart::find($_SESSION['cart_id']);
		}

		return null;
	}

	/**
	 * Get the number of items in the cart
	 */
	public static function cartQuantity(): int {
		return self::cart() ? self::cart()->orders->count() : 0;
	}

	/**
	 * Format a price
	 */
	public static function price(float $price, bool $forceNumber = false): string {
		if ($price || $forceNumber) {
			return StringHelper::formatWithComma($price) . ' €';
		} else {
			return __('Gratis');
		}
	}
}
