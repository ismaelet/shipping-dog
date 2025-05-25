<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NotSeller {

	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next): Response|RedirectResponse {
		$seller = Auth::user()?->seller;

		if ($seller) {
			return redirect()->route('seller');
		}

		return $next($request);
	}
}
