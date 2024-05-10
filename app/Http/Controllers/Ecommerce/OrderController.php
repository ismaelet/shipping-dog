<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller {

	/**
	 * * Action: Show
	 */
	public function show(Order $order, Request $request): View {
		return view('ecommerce.orders.show')
			->with([
				'title' => 'Ordine #' . $order->id,
				'order' => $order,
			]);
	}

	/**
	 * * Action: Destroy
	 */
	public function destroy(Order $order): RedirectResponse {
		$order->cancel();

		return redirect()->back();
	}

	/**
	 * * Custom action: Find
	 */
	public function find(Request $request) {
		redirect_old('/order/status');
	}
}
