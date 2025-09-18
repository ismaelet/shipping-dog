<?php

namespace App\Http\Middleware;

use App\Models\Ecommerce\ChangeRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasChangeRequestAccess {

	/**
	 * Handle an incoming request
	 */
	public function handle(Request $request, Closure $next): Response {
		$changeRequest = is_object($request->changeRequest) ? $request->changeRequest : ChangeRequest::find($request->changeRequest);

		if (
			!session()->has('change_request_id') ||
			session('change_request_id') != $changeRequest->id
		) {
			session()->forget('change_request_id');

			return redirect()->route('support.refund.search');
		}

		return $next($request);
	}
}
