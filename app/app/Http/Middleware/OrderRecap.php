<?php

namespace App\Http\Middleware;

use App\Helpers\EcommerceHelper;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderRecap
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $order = $request->order;

        // If the order is not found, redirect to the find route
        if (! $order) {
            return to_route('orders.find');
        }

        // If the order is open, redirect to the cart route
        if ($order->status == EcommerceHelper::statusFromTag('open')) {
            return to_route('cart');
        }

        // If there is a token in the request and it matches the order token, continue
        if ($request->token && $order->token == $request->token) {
            return $next($request);
        }

        // If the user is authenticated and the order belongs to the user, continue
        if (Auth::check() && $order->user_id == Auth::id()) {
            return $next($request);
        }

        // Otherwise, redirect to the find route
        return to_route('orders.find');
    }
}
