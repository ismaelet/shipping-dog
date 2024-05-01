<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Ecommerce\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartController extends Controller {

	/**
	 * Get the specific product data
	 */
	public function index(Request $request): AnonymousResourceCollection {
		$carts = Cart::join('addresses', 'carts.id', '=', 'addresses.cart_id')
			->where('carts.status', '!=', 0);

		// Filter the data
		if ($request->filter) {
			$carts = $carts->where(function ($query) use ($request) {
				$query->where('carts.id', 'like', '%' . $request->filter . '%')
					->orWhere('full_name', 'like', '%' . $request->filter . '%')
					->orWhereRaw("CONCAT(carts.id, ' - ', full_name) LIKE '%$request->filter%'");
			});
		}

		if ($request->where) {
			foreach ($request->where as $whereCondition) {
				$carts = $carts->where(...$whereCondition);
			}
		}

		$carts = $carts
			->orderByDesc('carts.id')
			->limit(10)
			->get('carts.*');

		return CartResource::collection($carts);
	}
}
