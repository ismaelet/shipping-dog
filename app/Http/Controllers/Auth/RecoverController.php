<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\GenericMail;
use App\Models\User;
use App\Models\User\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecoverController extends Controller {

	/**
	 * Render the recover page
	 */
	public function index() {

		return view('auth.recover')
			->with('locationTag', 'recover')
			->with('title', 'Recupero accesso');
	}

	/**
	 * Process the request
	 */
	public function store(Request $request) {

		// Validation
		$this->validate($request, [
			'email' => ['required', 'email'],
		]);

		// Process the request
		$email = $request->email;
		$user = User::where('email', $email)->first();

		if ($user) {
			// Generate reset security code
			$passwordReset = PasswordResetToken::where('email', $user->email)->first();

			if ($passwordReset) {
				$token = $passwordReset->token;
			} else {
				$token = Str::random(50);

				PasswordResetToken::create(
					[
						'email' => $user->email,
						'token' => $token,
					]
				);
			}

			// Generare the recovery endpoint
			$resetEndpoint = env('APP_URL') . '/reset?email=' . $user->email . '&token=' . $token;

			// Generate the email
			$subject = 'Recupero dati di accesso';
			$content = '
					<h2 style="text-align: center; padding-bottom: 32px; font-weight: 600;">Recupero dati</h2>
					<p>
						L\'indirizzo email associato al tuo account, necessario per accedere, è:<br>' . $email . '.<br><br>
						Per resettare la tua password puoi usare questo link:<br><a href="' . $resetEndpoint . '">' . $resetEndpoint . '</a>
					</p>';

			$mail = new GenericMail($subject, $content);

			// Send the email
			Mail::to($email)->send($mail);
		}

		return redirect()->route('auth.recover')
			->with('locationTag', 'recover')
			->with('title', 'Recupero accesso')
			->with('submitted', true)
			->with('email', $email)
			->with('not_found', !$user);
	}
}
