<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmptyCart {

	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next): Response|RedirectResponse {

		if (cart()?->id) {
			return redirect()->route('cart');
		}

		return $next($request);
	}
}
