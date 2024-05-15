<?php

namespace App\Http\Controllers;

use App\Mail\GenericMail;
use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Mail;

class EmergencyEmail extends Controller {
	public function sendEmail() {

		$orders = Order::whereIn('id', [62998, 62990]);

		$orders->each(function ($order) {
			$subject = 'Pagamento ordine #0' . $order->id;
			$full_name = $order->address->full_name;
			$name = explode(' ', $full_name)[0] ?? '';

			$content = 'Buongiorno ' . $name . ',<br>ricordiamo che il pagamento dell\'ordine #0' . $order->id . ' non è stato ancora effettuato.<br>Per favore, se intendi confermare l\'ordine provvedi al pagamento entro venerdì 17, altrimenti verrà cancellato. Puoi effettuare il pagamento direttamente dal tuo account o usando il link qui sotto.<br><br>
				<a href="' . $order->recapUrlWithToken() . '">Riepilogo ordine</a><br><br>
				<br><br>Grazie,<br>Lo staff di <strong>Shipping Dog</strong>';

			$email = $order->address->email;

			$mail = new GenericMail($subject, $content);

			// Mail::to($email)->send($mail);
		});
	}
}
