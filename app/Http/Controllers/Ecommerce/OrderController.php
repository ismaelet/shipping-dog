<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller {

	/**
	 * Show a specific order
	 */
	public function show(Order $order, Request $request): View {
		if ($request->token) {
			$order = Order::where('id', $order->id)
				->where('token', $request->token)
				->where('status', '!=', EcommerceHelper::statusFromTag('open'))
				->first();
		} else if (Auth::check()) {
			$order = Order::where('id', $order->id)
				->where('user_id', auth()->id())
				->where('status', '!=', EcommerceHelper::statusFromTag('open'))
				->first();
		}

		if (!$order) {
			redirect_old('/order/status');
		}

		return view('ecommerce.orders.show')
			->with([
				'title' => 'Ordine #' . $order->id,
				'order' => $order,
			]);
	}

	/**
	 * Reopen an order
	 *
	 * @param Order $order The order to reopen
	 * @return RedirectResponse Redirects to the cart page
	 */
	public function reopen(Order $order): RedirectResponse {
		// If the order is received
		if ($order->status == EcommerceHelper::statusFromTag('received')) {
			// Reopen the order
			$order->update([
				'status' => EcommerceHelper::statusFromTag('open'),
			]);

			// Redirect to the cart
			return to_route('cart');
		}

		return redirect()->back();
	}
}
