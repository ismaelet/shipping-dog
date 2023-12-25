<?php

use Ecommerce\Cart;

function ecommerce_send_status_email($cartId, $statusCode) {

	$orderList = '';
	$root = defined('PUBLIC_SERVER_ROOT') ? PUBLIC_SERVER_ROOT : SERVER_ROOT;
	$domain = defined('PUBLIC_CLIENT_URL') ? PUBLIC_CLIENT_URL : CLIENT_URL;
	$cart = new Cart($cartId);

	$mailContent = file_get_contents($root . '/app/templates/views/confirmation-email.html');
	$mailBody = Email::prepareBody($mailContent);

	// generate dynamic content
	switch ($statusCode) {
		case 'waiting-payment':
			$subject = $title = 'Ordine ricevuto';
			$info = 'Grazie per l\'acquisto su <a href="' . $domain . '">' . sys_config('name') . '</a>. L\'ordine è stato ricevuto e sarà processato al più presto.';
			break;
		case 'confirmed':
			$subject = $title = 'Pagamento confermato';
			$info = 'Abbiamo aggiornato lo stato dell\'ordine. Il nuovo stato è:<br><span style="display: block; font-size: 25px; font-weight: bold; letter-spacing: .05em; text-align: center;">CONFERMATO</span>';
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
				<td><p style="text-align: right">' . format_price($order->price) . '</p></td>
			</tr>';
	}

	// populate mail body
	$mailBody = str_replace('{{ title }}', $title, $mailBody);
	$mailBody = str_replace('{{ info }}', $info, $mailBody);
	$mailBody = str_replace('{{ cartId }}', $cart->idCode, $mailBody);
	$mailBody = str_replace('{{ cart-url }}', $cart->staticUrl, $mailBody);
	$mailBody = str_replace('{{ contacts }}', $cart->address->email . '<br>' . $cart->address->phone, $mailBody);
	$mailBody = str_replace('{{ address }}', $cart->address->fullAddress, $mailBody);
	$mailBody = str_replace('{{ payment }}', $cart->paymentMethod->name, $mailBody);
	$mailBody = str_replace('{{ shipping }}', $cart->shippingMethod->name, $mailBody);
	$mailBody = str_replace('{{ order-list }}', $orderList, $mailBody);
	$mailBody = str_replace('{{ shipping-price }}', $cart->shippingPriceString, $mailBody);
	$mailBody = str_replace('{{ total-price }}', $cart->priceString, $mailBody);

	//echo $mailBody;

	// setup mail config
	$mailData = [
		'debugger' => false,
		'from-address' => sys_config('email-username'),
		'from-name' => sys_config('name'),
		'to' => $cart->address->email,
		'subject' => $subject,
		'reply-address' => sys_config('contact-mail'),
		'text' => $mailBody,
	];

	// send mail
	Email::send($mailData);

	//gift cards
	/*if ($type == 'confirmed') {
		$giftCards = DB::select('id FROM ecommerce_orders WHERE cartId = ? AND itemId=61', [$cart->id]);

		while ($giftCardId = $giftCards->fetch_assoc()) {

			$giftCard = DB::row('* FROM ecommerce_gifrcards WHERE buyer')

			$$sender = new User();

			//setup mail config
			$mailData = [
				'debugger' => false,
				'from-address' => sys_config('mail-username'),
				'from-name' => 'Ritual Cosméticos',
				'to' => $cart->address->email,
				'subject' => $subject,
				'reply-address' => sys_config('contact-mail'),
				'text' => 'Vale presente',
			];

			//send mail
			Email::send($mailData);
		}
	}*/
}

function newsletter_send_email($mail, $ebookVersion = false) {

	$newCoupon = false;
	$mailBody = file_get_contents(SERVER_ROOT . '/app/templates/views/newsletter-email.html');

	//generate the coupon
	/*while (!$newCoupon) {
		$coupon = sprintf('%08d', rand(100, 99999999));
		if (!DB::exists('ecommerce_coupons WHERE code=?', [$coupon])) $newCoupon = true;
	}

	DB::add(
		'ecommerce_coupons (code, percentage, timeStart, timeEnd) VALUES (?, ?, ?, ?)',
		[$coupon, 10, date('Y-m-d'), date('Y-m-d', strtotime('+1 year'))]
	);*/
	$coupon = 'RITUAL';

	//populate mail body
	$mailBody = str_replace('{{ coupon }}', $coupon, $mailBody);

	//echo $mailBody;

	//setup mail config
	$mailData = [
		'debugger' => false,
		'from-address' => sys_config('mail-username'),
		'from-name' => 'Ritual Cosméticos',
		'to' => $mail,
		'subject' => 'Obrigado por subscrever a newsletter',
		'reply-address' => sys_config('contact-mail'),
		'text' => $mailBody,
	];

	//send mail
	Email::send($mailData);
}

function send_tracking_email($cartId) {

	$root = defined('PUBLIC_SERVER_ROOT') ? PUBLIC_SERVER_ROOT : SERVER_ROOT;

	$cart = new Cart($cartId);

	$content = file_get_contents($root . '/app/templates/views/tracking-email.html');

	//populate mail body
	$content = str_replace('{{ cartId }}', $cart->id, $content);
	$content = str_replace('{{ tracking-code }}', $cart->tracking, $content);

	//setup mail config
	$mailData = [
		'debugger' => false,
		'from-address' => sys_config('mail-username'),
		'from-name' => sys_config('name'),
		'to' => $cart->address->email,
		'subject' => 'Seu pedido foi enviado',
		'reply-address' => sys_config('contact-mail'),
		'text' => $content,
	];

	//send mail
	Email::send($mailData);
}
