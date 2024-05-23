<?php

namespace App\Http\Controllers;

use App\Mail\GenericMail;
use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Mail;

class EmergencyEmail extends Controller {
	public function sendEmail() {

		$orders = Order::whereIn('id', [62765]);

		$orders->each(function ($order) {
			$subject = 'Pagamento ordine #0' . $order->id;
			$full_name = $order->address->full_name;
			$name = explode(' ', $full_name)[0] ?? '';

			$content = 'Buongiorno ' . $name . ',<br>abbiamo visto che hai provato a completare il pagamento, ma non è andato a buon fine, putroppo l\'errore è collegato al fatto che il proprietario del negozio <strong>NEAPOLITAN FRESH MEMES</strong> ha rimosso uno dei prodotti che stavi cercando di acquistare. Abbiamo però risolto il problema ed ora è possibile acquistare anche prodotti rimossi, se già aggiunti al carrello, quindi se vuoi completare l\'acquisto puoi farlo tramite il link qui sotto:<br><br>
				<a href="' . $order->recapUrlWithToken() . '">Riepilogo ordine</a><br><br>
				Ci scusiamo nuovamente per i molteplici problemi riscontrati e auguriamo una buona giornata.<br><br>
				<br><br>Grazie,<br>Lo staff di <strong>Shipping Dog</strong>';

			$email = $order->address->email;

			$mail = new GenericMail($subject, $content);

			// Mail::to($email)->send($mail);
		});
	}
}
