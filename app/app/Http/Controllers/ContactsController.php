<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
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
		]);
	}

	public function send(Request $request): RedirectResponse {
		$name = trim($_POST['name']);
		$clientEmail = trim($_POST['email']);
		$mailText = trim($_POST['text']);

		$serv = explode('@', $clientEmail)[1] ?? null;
		$serv = explode('.', $serv)[0];

		// Spam filters
		if (!$serv || !in_array($serv, ['tiscali', 'gmail', 'libero', 'yahoo', 'hotemail', 'icloud', 'outlook'])) {
			return redirect()->back()->with([
				'error' => '<span class="font-bold">Errore</span>: quest\'indirizzo è stato bloccato dai nostri filtri anti-spam. Puoi comunque a scriverci direttamente a
							<a href="' . config('custom.contacts.email.url') . '" class="font-bold">' . config('custom.contacts.email.label') . '</a>, invece di usare questo formulario.',
			]);
		}

		if (preg_match("/(sexy|bitcoin|wellness|dollars|free|business|https:)/i", $mailText)) {
			return redirect()->back()->with([
				'error' => '<span class="font-bold">Errore</span>: il messaggio non ha superato i nostri filtri anti-spam. Puoi comunque a scriverci direttamente a
							<a href="' . config('custom.contacts.email.url') . '" class="font-bold">' . config('custom.contacts.email.label') . '</a>, invece di usare questo formulario.',
			]);
		}

		// Send message
		$content = '<h1 style="text-align: center; padding-bottom: 16px; font-weight: 600;">Richiesta informazioni</h1>
		<p>Hai ricevuto una richiesta di informazioni da parte di <strong>' . $name . '</strong> (<a href="mailto:' . $clientEmail . '">' . $clientEmail . '</a>) con la seguente domanda:<br><br>"' . $mailText . '"</p>';

		Mail::to(config('custom.contacts.email.label'))
			->send(new ContactMail(
				email: $clientEmail,
				name: $name,
				text: $content
			));

		return redirect()->back()->with(
			'success',
			'Messaggio inviato, ti risponderemo al più presto'
		);
	}
}
