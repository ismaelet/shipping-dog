<?php

namespace App\Http\Middleware;

use App\Helpers\EcommerceHelper;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class Cart {

	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next): Response|RedirectResponse {
		$pageStepTag = explode('.', Route::currentRouteName())[1] ?? null;
		$pageStep = EcommerceHelper::stepFromTag($pageStepTag);
		$cartStepTag = cart()?->stepTag();
		$cartStep = cart()?->step;

		// If the cart is empty, redirect to the empty cart page
		if (!cart()) {
			return redirect()->route('cart.empty');
		}

		// If the step isn't valid redirect to the first step
		if (!$cartStep) {
			$firstStep = reset(config('ecommerce.cart.steps'));

			return redirect()->route('cart.' . $firstStep);
		}

		// If the page step is more advanced than the cart step, redirect to the cart step
		if ($pageStep > $cartStep) {
			return redirect()->route('cart.' . $cartStepTag);
		}

		return $next($request);
	}
}
