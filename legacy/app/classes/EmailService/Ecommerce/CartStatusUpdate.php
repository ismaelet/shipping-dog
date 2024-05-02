<?php

namespace EmailService\Ecommerce;

use Email;

class orderstatusUpdate {
	public static function send(object $order = null, string $statusCode = null, bool $forceSend = false, bool $debug = false) {
		$order = $order ?: cart_old();
		$orderItemList = '';
		$statusCode = $statusCode ?: $order->statusCode;
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
		foreach ($order->order_items as $orderItem) {
			$orderItemList .= '
			<tr style="border-bottom: 1px solid #f4f4f4">
				<td><p style="line-height: 3">' . $orderItem->name . '</p></td>
				<td><p style="text-align: right">' . $orderItem->priceString . '</p></td>
			</tr>';
		}

		// populate mail body
		$emailContent = str_replace('{{ title }}', $title, $emailContent);
		$emailContent = str_replace('{{ info }}', $info, $emailContent);
		$emailContent = str_replace('{{ order_id }}', $order->idCode, $emailContent);
		$emailContent = str_replace('{{ cart-url }}', $order->staticUrl, $emailContent);
		$emailContent = str_replace('{{ contacts }}', $order->address->email . '<br>' . $order->address->phone, $emailContent);
		$emailContent = str_replace('{{ address }}', $order->address->fullAddress, $emailContent);
		$emailContent = str_replace('{{ payment }}', $order->paymentMethod->name ?: '-', $emailContent);
		$emailContent = str_replace('{{ shipping }}', $order->shippingMethod->name, $emailContent);
		$emailContent = str_replace('{{ order-list }}', $orderItemList, $emailContent);
		$emailContent = str_replace('{{ shipping-price }}', $order->shippingFeeString, $emailContent);
		$emailContent = str_replace('{{ total-price }}', $order->priceString, $emailContent);

		$emailBody = Email::prepareBody($emailContent);

		// setup mail config
		$emailData = [
			'debug' => $debug,
			'reply-address' => sys_config('contact-email'),
			'reply-name' => sys_config('name'),
			'to' => $order->address->email,
			'subject' => $subject,
			'text' => $emailBody,
		];

		if ($statusCode == 'received' || $forceSend) {
			Email::send($emailData);
		}
	}
}
