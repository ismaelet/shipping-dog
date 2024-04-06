<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
			'username' => [
				'required',
				Rule::unique('users', 'username'),
			],
			'password' => ['required', 'confirmed'],
			'email' => [
				'required',
				'email',
				Rule::unique('users', 'email'),
			],
		]);

		// Store the user
		User::create([
			'username' => $request->username,
			'password' => Hash::make($request->password),
			'email' => $request->email,
		]);

		// Sign in the user
		auth()->attempt($request->only('email', 'password'));

		// Redirect
		return redirect('/profile');
	}
}
