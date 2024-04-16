<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Cart;

class CartController extends Controller {

	public static function get(): array|false {
		$filter = $_GET['filter'];

		$carts = Cart::join('addresses', 'carts.id', '=', 'addresses.cart_id')
			->where('carts.status', '!=', 0)
			->where(function ($query) use ($filter) {
				$query->where('carts.id', 'like', '%' . $filter . '%')
					->orWhere('full_name', 'like', '%' . $filter . '%');
			})
			->orderBy('carts.id', 'desc')
			->limit(10)
			->select('carts.id as id', 'addresses.full_name as name')
			->get();

		if ($carts->count()) {
			$results = [];

			foreach ($carts as $cart) {
				$results[] = [
					'id' => $cart->id,
					'name' => $cart->id . ' - ' . $cart->name
				];
			}

			return $results;
		}

		return false;
	}
}
