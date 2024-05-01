<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {

	/**
	 * Render the login page
	 */
	public function index() {
		return view('auth.login')
			->with('locationTag', 'login')
			->with('title', 'Login');
	}

	/**
	 * Authenticate the user
	 */
	public function auth(Request $request): RedirectResponse {
		// Validate the request 
		$credentials = $request->validate([
			'login' => ['required'],
			'password' => ['required'],
		]);

		// Determine if login input is email or username
		$fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$credentials[$fieldType] = $credentials['login'];
		unset($credentials['login']);

		// Attempt to authenticate the user
		if (Auth::attempt($credentials, $request->filled('remember'))) {
			$request->session()->regenerate();

			if ($request->has('redirect')) {
				return back();
			} else {
				return redirect('/profile');
			}
		}

		return back()->with([
			'error' => 'I dati di accesso sono errati.',
		])->onlyInput('login');
	}
}
