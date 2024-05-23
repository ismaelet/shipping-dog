<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller {

	/**
	 * * Index: Access
	 */
	public function indexAccess(): View|RedirectResponse {
		// If the user is logged in, advance to the next step
		if (Auth::check()) {
			cart()->advance();

			return redirect()->route('cart.' . cart()->stepTag());
		}

		// Otherwise, show the access page
		return view('ecommerce.cart.access')
			->with([
				'title' => 'Accedi | Carrello',
				'pageStep' => EcommerceHelper::stepFromTag('access'),
				'orderStep' => cart()->step,
			]);
	}

	/**
	 * * Custom: Guest login
	 */
	public function guestLogin(): RedirectResponse {
		// If the user is a guest, advance to the next step
		cart()->advance();

		return redirect()->route('cart.' . cart()->stepTag());
	}
}
