<?php

namespace App\Helpers;

use App\Models\Ecommerce\Cart;
use Illuminate\Support\Facades\Auth;

class EcommerceHelper {

	/**
	 * Get the ID of the active cart.
	 *
	 * @return int|null The ID of the active cart, or null if no active cart is found
	 */
	public static function cartId(): ?int {
		// If the user is logged in, return the ID of their cart
		if (auth()->check() && auth()->user()->cart) {
			return auth()->user()->cart->id;
		}

		// Otherwise, return the ID of the cart stored in the session
		$sessionCart = Cart::find(session('cart_id'));

		if ($sessionCart && !$sessionCart->status) {
			return $sessionCart->id;
		}

		// If no cart is found, return null
		return null;
	}

	/**
	 * Get the active cart
	 *
	 * @return Cart|null The active cart if it exists, otherwise null
	 */
	public static function cart(): ?Cart {
		if (auth()->check()) {
			return Auth::user()->cart ?? null;
		}

		if (session('cart_id')) {
			return Cart::find(session('cart_id'));
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
	public static function formatPrice(float $price, bool $forceNumber = false): string {
		if ($price || $forceNumber) {
			return StringHelper::formatWithComma($price) . ' €';
		} else {
			return __('Gratis');
		}
	}

	/**
	 * Get the step index based on the given step tag.
	 *
	 * @param string $stepTag The step tag to search for.
	 * @return int|false The step index if found, false otherwise.
	 */
	public static function stepFromTag(string $stepTag): int|false {
		$stepKeyPosition = array_search($stepTag, array_column(config('ecommerce.cart.steps'), 'tag'));

		if ($stepKeyPosition === false) {
			return false;
		}

		return array_keys(config('ecommerce.cart.steps'))[$stepKeyPosition];
	}
}
