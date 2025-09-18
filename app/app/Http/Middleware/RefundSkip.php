<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Ecommerce\RefundController;
use App\Models\Ecommerce\ChangeRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefundSkip {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response {
		if (session()->has('change_request_id')) {
			$changeRequest = ChangeRequest::find(session('change_request_id'));

			if ($changeRequest) {
				return redirect()->route('support.refund.' . RefundController::STEPS[$changeRequest->step], $changeRequest);
			}
		}

		return $next($request);
	}
}
