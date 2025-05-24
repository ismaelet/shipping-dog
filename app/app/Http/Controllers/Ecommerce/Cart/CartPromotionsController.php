<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartPromotionsController extends Controller {

	/**
	 * View the cart promotions page
	 * 
	 * @return View|RedirectResponse
	 */
	public function index(): View|RedirectResponse {
		return $this->skip();

		return view('ecommerce.cart.promotions')
			->with([
				'title' => 'Offerte',
			]);
	}

	/**
	 * Skip the promotions step
	 * 
	 * @return RedirectResponse
	 */
	private function skip(): RedirectResponse {
		cart()->advance();

		return redirect()->route('cart.' . cart()->stepTag());
	}
}
