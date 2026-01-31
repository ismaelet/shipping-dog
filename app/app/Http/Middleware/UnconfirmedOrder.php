<?php

namespace App\Http\Middleware;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnconfirmedOrder {

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse {
        $order = null;

        if ($request->route('order')) {
            $order = $request->route('order');
        }

        if ($request->order_id) {
            $order = Order::find($request->order_id);
        }

        if ($order && in_array($order->status, [EcommerceHelper::status('open'), EcommerceHelper::status('received')])) {
            return $next($request);
        }

        return redirect()->route('cart');
    }
}
