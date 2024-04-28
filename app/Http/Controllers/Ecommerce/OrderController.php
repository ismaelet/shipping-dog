<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller {

	/**
	 * * Create a new order
	 */
	public function create(Request $request): RedirectResponse {
		$config['product_id'] = $productId;
		$config['quantity'] = $_POST['quantity'];

		if (isset($_POST['cbx-color'])) {
			foreach ($_POST['cbx-color'] as $config['color_id']);
		}
		if (isset($_POST['cbx-size'])) {
			foreach ($_POST['cbx-size'] as $config['size_id']);
		}
		if (isset($_POST['cbx-gender'])) {
			foreach ($_POST['cbx-gender'] as $config['genderId']);
		}

		if (isset($_POST['logo-productId'])) {
			$config['logo_id'] = $_POST['logo-productId'];
		}

		if (isset($_POST['cbx-pos'])) {
			foreach ($_POST['cbx-pos'] as $config['position_id']);
		} else {
			$config['position_id'] = $oldProduct->availablePositionIds[0];
		}

		Order::add($config);

		redirect_old('/cart');
	}
}
