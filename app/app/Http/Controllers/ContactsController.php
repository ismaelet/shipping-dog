<?php

namespace App\Http\Controllers;

use App\Mail\GenericMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactsController extends Controller {

	/**
	 * Display the contacts page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		return view('support.contacts', [
			'title' => 'Contatti',
			'locationTag' => 'support',
			'sent' => false,
			'error' => false,
		]);
	}

	public function send(Request $request): RedirectResponse {
		$sent = false;
		$error = false;

		$name = trim($_POST['name']);
		$clientEmail = trim($_POST['email']);
		$mailText = trim($_POST['text']);

		$serv = explode('@', $clientEmail)[1] ?? null;
		$serv = explode('.', $serv)[0];

		if (!$serv || !in_array($serv, ['tiscali', 'gmail', 'libero', 'yahoo', 'hotemail', 'icloud', 'outlook'])) {
			$error = 'email_bad';
		} else if (preg_match("/(sexy|bitcoin|wellness|dollars|free|business)/i", $mailText)) {
			$error = 'word_bad';
		} else {
			$subject = 'Richiesta informazioni da ' . $name . ' (' . $clientEmail . ')';
			$content = '<h1 style="text-align: center; padding-bottom: 16px; font-weight: 600;">Richiesta informazioni</h1>
		<p>Hai ricevuto una richiesta di informazioni da parte di <strong>' . $name . '</strong> (<a href="mailto:' . $clientEmail . '">' . $clientEmail . '</a>) con la seguente domanda:<br><br>"' . $mailText . '"</p>';

			Mail::to(config('custom.contacts.email.label'))
				->send(new GenericMail(
					subject: $subject,
					content: $content,
					replyTo: [$clientEmail, $name]
				));

			$sent = true;
		}

		return redirect()->route('contacts')->with([
			'sent' => $sent,
			'error' => $error,
		]);
	}
}
