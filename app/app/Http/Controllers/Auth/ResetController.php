<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetController extends Controller {

	/**
	 * Render the reset page
	 */
	public function index() {

		$email = $_GET['email'] ?? null;
		$token = $_GET['token'] ?? null;

		return view('auth.reset')
			->with([
				'title' => 'Reset accesso',
				'email' => $email,
				'token' => $token,
			]);
	}

	/**
	 * Process the request
	 */
	public function update(Request $request) {

		// Validation
		$this->validate($request, [
			'email' => ['required'],
			'token' => ['required'],
			'password' => ['required', 'confirmed'],
		]);

		// Get the reset data
		$email = $request->email;
		$token = $request->token;
		$user = User::where('email', $email)->first();
		$passwordReset = PasswordResetToken::where('email', $email)->first();
		$error = !$user || !$passwordReset || $user->email != $email || $token != $passwordReset->token;

		if ($error) {
			return back()->with('error', 'Il link di rest non è valido o è scaduto');
		}

		$passwordReset->delete();

		$user->update([
			'password' => Hash::make($request->password),
		]);

		return back()->with('submitted', true);
	}
}
