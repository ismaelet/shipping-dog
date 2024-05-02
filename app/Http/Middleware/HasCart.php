<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HasCart {

	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next): Response|RedirectResponse {

		if (!cart()) {
			return to_route('cart.empty');
		}

		return $next($request);
	}
}
