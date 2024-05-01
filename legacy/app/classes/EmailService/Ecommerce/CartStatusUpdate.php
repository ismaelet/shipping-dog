<?php

namespace EmailService\Ecommerce;

use Email;

class CartStatusUpdate {
	public static function send(object $cart = null, string $statusCode = null, bool $forceSend = false, bool $debug = false) {
		$cart = $cart ?: cart_old();
		$orderList = '';
		$statusCode = $statusCode ?: $cart->statusCode;
		$emailContent = file_get_contents(base_path() . '/public/templates/assets/template-emails/cart-update.html');

		// generate dynamic content
		switch ($statusCode) {
			case 'received':
			case 'waiting-payment':
				$subject = $title = 'Ordine ricevuto';
				$info = 'Grazie per l\'acquisto su <a href="' . sys_config('domain') . '">' . sys_config('name') . '</a>. L\'ordine è stato ricevuto e sarà processato al più presto.';
				break;

			case 'confirmed':
				$subject = $title = 'Pagamento confermato';
				$info = 'Abbiamo aggiornato lo stato dell\'ordine. Il nuovo stato è:<br><span style="display: block; font-size: 25px; font-weight: bold; letter-spacing: .05em; text-align: center;">CONFERMATO</span>';
				break;

			case 'label-ready':
				$subject = $title = 'Ordine pronto';
				$info = 'Abbiamo aggiornato lo stato dell\'ordine. Il nuovo stato è:<br><span style="display: block; font-size: 25px; font-weight: bold; letter-spacing: .05em; text-align: center;">PRONTO</span>';
				break;

			case 'shipped':
				$subject = $title = 'Ordine spedito';
				$info = 'Abbiamo aggiornato lo stato dell\'ordine. Il nuovo stato è:<br><span style="display: block; font-size: 25px; font-weight: bold; letter-spacing: .05em; text-align: center;">SPEDITO</span>';
				break;

			case 'delivered':
				$subject = $title = 'Ordine consegnato';
				$info = 'Abbiamo aggiornato lo stato dell\'ordine. Il nuovo stato è:<br><span style="display: block; font-size: 25px; font-weight: bold; letter-spacing: .05em; text-align: center;">CONSEGNATO</span>';
				break;

			case 'canceled':
				$subject = $title = 'Ordine cancellato';
				$info = '<span style="text-align: center;">Il suo ordine è stato cancellato.</span>';
				break;
		}

		// generate order list
		foreach ($cart->orders as $order) {
			$orderList .= '
			<tr style="border-bottom: 1px solid #f4f4f4">
				<td><p style="line-height: 3">' . $order->name . '</p></td>
				<td><p style="text-align: right">' . $order->priceString . '</p></td>
			</tr>';
		}

		// populate mail body
		$emailContent = str_replace('{{ title }}', $title, $emailContent);
		$emailContent = str_replace('{{ info }}', $info, $emailContent);
		$emailContent = str_replace('{{ cart_id }}', $cart->idCode, $emailContent);
		$emailContent = str_replace('{{ cart-url }}', $cart->staticUrl, $emailContent);
		$emailContent = str_replace('{{ contacts }}', $cart->address->email . '<br>' . $cart->address->phone, $emailContent);
		$emailContent = str_replace('{{ address }}', $cart->address->fullAddress, $emailContent);
		$emailContent = str_replace('{{ payment }}', $cart->paymentMethod->name ?: '-', $emailContent);
		$emailContent = str_replace('{{ shipping }}', $cart->shippingMethod->name, $emailContent);
		$emailContent = str_replace('{{ order-list }}', $orderList, $emailContent);
		$emailContent = str_replace('{{ shipping-price }}', $cart->shippingFeeString, $emailContent);
		$emailContent = str_replace('{{ total-price }}', $cart->priceString, $emailContent);

		$emailBody = Email::prepareBody($emailContent);

		// setup mail config
		$emailData = [
			'debug' => $debug,
			'reply-address' => sys_config('contact-email'),
			'reply-name' => sys_config('name'),
			'to' => $cart->address->email,
			'subject' => $subject,
			'text' => $emailBody,
		];

		if ($statusCode == 'received' || $forceSend) {
			Email::send($emailData);
		}
	}
}
