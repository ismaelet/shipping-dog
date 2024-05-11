<?php

namespace App\Http\Controllers;

use App\Mail\GenericMail;
use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Mail;

class EmergencyEmail extends Controller {
	public function sendEmail() {

		// $firstOrder = Order::where('status', '>', 0)
		// 	->where('payment_method_id', 1)
		// 	->where('token', '!=', '')
		// 	->oldest()
		// 	->first();

		// $lastOrder = Order::find(62985);

		// $problemOrders = Order::where('status', '>', 0)
		// 	->where('payment_method_id', 1)
		// 	->where('created_at', '>=', $firstOrder->created_at)
		// 	->oldest()
		// 	->get();

		// $problemOrders->each(function ($order) use ($lastOrder) {
		// 	$order->update([
		// 		'status' => 1,
		// 	]);

		// 	$subject = 'Errore pagamento Shipping Dog';
		// 	$full_name = $order->address->full_name;
		// 	$name = explode(' ', $full_name)[0] ?? '';

		// 	if ($order->created_at > $lastOrder->created_at) {
		// 		$content = 'Buongiorno ' . $name . ',<br>purtroppo dal <strong>3 maggio</strong> a oggi abbiamo riscontrato dei problemi con i pagamenti effettuati sul nostro sito tramite <strong>PayPal</strong><br> Alcuni ordini, incluso il tuo, sono stati confermati senza che alcun prelivo sia stato effettuato.<br><br>Se desideri completare il tuo ordine, ti preghiamo di verificare sul tuo account PayPal che il pagamento non sia avvenuto e ad effettuarlo usando il link qui sotto, che aprirà la pagina del tuo ordine, e cliccando poi su <strong>Procedi al pagamento</strong>.<br><br>
		// <a href="' . $order->recapUrlWithToken() . '">Link al riepilogo dell\'ordine</a>
		// <br><br>Scusaci per il disagio e grazie per la tua comprensione.<br><br>Lo staff di Shipping Dog';
		// 	} else {
		// 		$content = 'Buongiorno ' . $name . ',<br>purtroppo dal <strong>3 maggio</strong> a oggi abbiamo riscontrato dei problemi con i pagamenti effettuati sul nostro sito tramite <strong>PayPal</strong><br> Alcuni ordini, incluso il tuo, sono stati confermati senza che alcun prelivo sia stato effettuato.<br><br>Il tuo ordine è già stato spedito, quindi lo riceverai, o lo hai già ricevuto, in ogni caso, ma ti inviteremmo a controllare sul tuo account PayPal che il pagamento effettivamente non sia avvenuto e ad effettuarlo usando il link qui sotto, che aprirà la pagina del tuo ordine, e cliccando poi su <strong>Procedi al pagamento</strong>.<br><br>
		// <a href="' . $order->recapUrlWithToken() . '">Link al riepilogo dell\'ordine</a>
		// <br><br>Scusaci per il disagio e grazie per la tua comprensione.<br><br>Lo staff di Shipping Dog';
		// 	}


		// 	$email = $order->address->email;

		// 	$mail = new GenericMail($subject, $content);

		// 	Mail::to($email)->send($mail);
		// });
	}
}
