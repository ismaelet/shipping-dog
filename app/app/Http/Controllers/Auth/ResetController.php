<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\PasswordResetToken;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetController extends Controller {

	/**
	 * Render the reset password page.
	 * 
	 * @param Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		$email = $request->email;
		$token = $request->token;

		return view('auth.reset-password')
			->with([
				'title' => 'Reset accesso',
				'email' => $email,
				'token' => $token,
			]);
	}

	/**
	 * Process the request.
	 * 
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request): RedirectResponse {
		// Validate the request
		$request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:8|confirmed',
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),

			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		return $status === Password::PasswordReset
			? redirect()->route('login')->with('status', __($status))
			: back()->withErrors(['email' => [__($status)]]);

		// Get the reset data
		// $email = $request->email;
		// $token = $request->token;
		// $user = User::where('email', $email)->first();
		// $passwordReset = PasswordResetToken::where('email', $email)->first();
		// $error = !$user || !$passwordReset || $user->email != $email || $token != $passwordReset->token;

		// if ($error) {
		// 	return back()->with('error', 'Il link di rest non è valido o è scaduto');
		// }

		// $passwordReset->delete();

		// $user->update([
		// 	'password' => Hash::make($request->password),
		// ]);

		// return back()->with('submitted', true);
	}
}
