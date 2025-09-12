<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller {

	/**
	 * Index action
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index(): View {
		return view('auth.login')
			->with('locationTag', 'login')
			->with('title', 'Login');
	}

	/**
     * Custom action: Authenticate the user
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function auth(Request $request): RedirectResponse {
		// Validate the request 
		$credentials = $request->validate([
			'login' => 'required|string',
			'password' => 'required|string',
			'remember' => 'boolean',
		]);

		// Determine if login input is email or username
		$fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		// Attempt to authenticate the user
		if (Auth::attempt([$fieldType => $request->login, 'password' => $request->password], $request->filled('remember'))) {
			// Merge the guest cart with the user cart
			$this->mergeGuestCart();

			// Regenerate the session
			$request->session()->regenerate();

			if ($request->has('redirect')) {
				// Redirect to the previous page
				return back();
			} else {
				// Redirect to the user dashboard
				return to_route('user-dashboard');
			}
		}

		// If the authentication fails, redirect back with an error message
		return back()->withErrors([
			'login' => 'Le credenziali fornite non sono corrette.',
		])->onlyInput('login');
	}

	/**
	 * Merge the guest cart with the user cart.
	 * If the user has a cart, merge the guest cart with the user cart by updating the order_id of each order in the guest cart to the user cart's id.
	 * If the user does not have a cart, assign the guest cart to the user by updating the user_id of the guest cart to the authenticated user's id.
	 * If there is no guest cart no action is taken.
	 *
	 * @return void
	 */
	private function mergeGuestCart(): void {
		if (session('order_id')) {
			// Find the guest cart
			$guestCart = Order::where('id', session('order_id'))
				->where('status', 0)
				->first();

			if ($guestCart) {
				// Find the user cart
				$userCart = Auth::user()->cart;

				if ($userCart) {
					// If the user has a cart, merge the guest cart with the user cart
					$guestCart->order_items->each(function (OrderItem $orderItem) use ($userCart) {
						$orderItem->order_id = $userCart->id;
						$orderItem->save();
					});

					$guestCart->delete();
				} else {
					// If the user does not have a cart, assign the guest cart to the user
					$guestCart->user_id = Auth::id();
				}
			}
		}
	}
}
