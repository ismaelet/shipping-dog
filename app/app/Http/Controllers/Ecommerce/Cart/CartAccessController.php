<?php

namespace App\Http\Controllers\Ecommerce\Cart;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartAccessController extends Controller {

	/**
	 * View the cart access page
	 * 
	 * @return View|RedirectResponse
	 */
	public function index(): View|RedirectResponse {
		// If the user is logged in, advance to the next step
		if (Auth::check()) {
			cart()->advance();

			return redirect()->route('cart.' . cart()->stepTag());
		}

		// Otherwise, show the access page
		return view('ecommerce.cart.access')
			->with([
				'title' => 'Accedi',
			]);
	}

	/**
	 * Handle guest login
	 * 
	 * @return RedirectResponse
	 */
	public function guestLogin(): RedirectResponse {
		cart()->advance();

		return redirect()->route('cart.' . cart()->stepTag());
	}
}
