<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Ecommerce\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller {

	/**
	 * Get a list of orders
	 */
	public function index(Request $request): AnonymousResourceCollection {
		$orderItems = Order::join('addresses', 'orders.id', '=', 'addresses.order_id')
			->where('orders.status', '!=', 0);

		// Filter the data
		if ($request->filter) {
			$orderItems = $orderItems->where(function ($query) use ($request) {
				$query->where('orders.id', 'like', '%' . $request->filter . '%')
					->orWhere('full_name', 'like', '%' . $request->filter . '%')
					->orWhereRaw("CONCAT(orders.id, ' - ', full_name) LIKE '%$request->filter%'");
			});
		}

		if ($request->where) {
			foreach ($request->where as $whereCondition) {
				$orderItems = $orderItems->where(...$whereCondition);
			}
		}

		$orderItems = $orderItems
			->orderByDesc('orders.id')
			->limit(10)
			->get('orders.*');

		return OrderResource::collection($orderItems);
	}

	/**
	 * Get a specific order
	 */
	public function show(Order $order): OrderResource {


		return new OrderResource($order);
	}

	/**
	 * Update the specified order
	 */
	public function update(Request $request, Order $order): OrderResource {
		$validated = $request->validate([
			'shipping_method_id' => 'integer',
		]);

		$order->update($validated);

		return new OrderResource($order);
	}
}
