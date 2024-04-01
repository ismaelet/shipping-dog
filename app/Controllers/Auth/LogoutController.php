<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller {

	/**
	 * Render the logout page
	 */
	public function index() {
		return view('auth.logout')
			->with('locationTag', 'logout')
			->with('title', 'Logout');
	}

	/**
	 * Authenticate the user
	 */
	public function store(Request $request) {
		auth()->logout();

		return redirect()->route('login');
	}
}
