<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Newsletter\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller {
	public function index() {
		return view('auth.register')
			->with('locationTag', 'login')
			->with('title', 'Registrati');
	}

	public function store(Request $request) {
		// Validation
		$this->validate($request, [
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
			'email' => [
				'required',
				'email',
				'max:255',
				Rule::unique('users', 'email'),
			],
			'password' => 'required|confirmed',
		]);

		// Store the user
		User::create([
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
		]);

		Subscriber::createAndSend([
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
		]);

		// Sign in the user
		auth()->attempt($request->only('email', 'password'));

		// Redirect
		return redirect()->route('user-dashboard');
	}
}
