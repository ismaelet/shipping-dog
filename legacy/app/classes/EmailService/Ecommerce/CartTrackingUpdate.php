<?php

namespace EmailService\Ecommerce;

use Email;



class CartTrackingUpdate {
	public static function send(object $cart = null, bool $debug = false) {
		$cart = $cart ?: cart_old();
		$emailContent = file_get_contents(public_server_path('/app/templates/assets/emails/cart-tracking.html'));

		// populate mail body
		$emailContent = str_replace('{{ cartId }}', $cart->id, $emailContent);
		$emailContent = str_replace('{{ tracking-code }}', $cart->tracking, $emailContent);

		// setup mail config
		$emailData = [
			'debug' => $debug,
			'reply-address' => sys_config('contact-email'),
			'reply-name' => sys_config('name'),
			'to' => $debug ? 'ismaele.tuffanelli@gmail.com' : $cart->address->email,
			'subject' => 'Notifica di spedizione',
			'text' => $emailContent,
		];

		// send mail
		if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || $debug) {
		}
	}
}
