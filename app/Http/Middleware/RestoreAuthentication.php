<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestoreAuthentication {

	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next): Response {
		login_direct();

		return $next($request);
	}
}
