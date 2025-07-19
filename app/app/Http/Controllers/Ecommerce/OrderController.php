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
	 * Resource action: Show
	 */
	public function show(Order $order, Request $request): View {
		$order->load(
			'order_items',
			'order_items.color',
			'order_items.position',
			'order_items.product',
			'order_items.size',
			'order_items.product.type',
			'order_items.product_with_trashed',
			'order_items.product_with_trashed.design_with_trashed',
			'order_items.product_with_trashed.type',
		);

		return view('ecommerce.orders.show')
			->with([
				'title' => 'Ordine #' . $order->id,
				'order' => $order,
			]);
	}

	/**
	 * Resource action: Update
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
	 * Resource action: Destroy
	 */
	public function destroy(Order $order): RedirectResponse {
		$order->load(
			'order_items',
			'order_items.seller',
			'order_items.seller.order_items',
		);

		$order->cancel();

		return redirect()->back();
	}
}
