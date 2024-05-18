<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\EcommerceHelper;
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
	 * * Action: Update
	 */
	public function update(Order $order, Request $request): RedirectResponse {
		$request->validate([
			'status' => 'integer',
		]);

		if ($request->has('status')) {
			if ($request->status == EcommerceHelper::statusFromTag('cancelled')) {
				$order->cancel();
			} else if ($request->status == EcommerceHelper::statusFromTag('confirmed')) {
				$order->confirm(false, false);
			} else {
				$order->update($request->only('status'));
			}

			$order->status = $request->status;
			$order->save();
		}

		return redirect()->back();
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
