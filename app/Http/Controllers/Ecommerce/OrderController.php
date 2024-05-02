<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
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
}
