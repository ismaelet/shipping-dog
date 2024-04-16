<?php

function send_email($email, $cart_id) {

	//generate email text
	$user_id = DBOld::find('user_id FROM carts WHERE id = ?', [$cart_id]);

	$row_addr = DBOld::row('* FROM addresses WHERE cart_id = ?', [$cart_id]);

	$addr_full_name = $row_addr['full_name'];
	$addr_strt = $row_addr['address'];
	$addr_num = $row_addr['number'];
	$addr_zip = $row_addr['zip'];
	$addr_city = $row_addr['city'];
	$addr_reg = $row_addr['region'];

	$security = $row_addr['security'];
	$toemail = $row_addr['email'];

	$orders = '';
	$res_ord = DBOld::select('id FROM orders WHERE cart_id = ? ORDER BY id ASC', [$cart_id]);

	while ($row_ord = $res_ord->fetch_assoc()) {

		$order = new Order($row_ord['id']);

		$orders .= $order->desc['normal'] . '<br>';
	}

	$address =
		$addr_full_name . '<br>' .
		$addr_strt . ', ' . $addr_num . '<br>' .
		$addr_zip . ' ' . $addr_city . ' (' . $addr_reg . ')';

	$txt = file_get_contents(SERVER_ROOT . '/app/functions/email/order_confirmation.html');

	$txt = str_replace('{{email}}', sys_config('contact-email'), $txt);
	$txt = str_replace('{{cart_id}}', $cart_id, $txt);
	$txt = str_replace('{{address}}', $address, $txt);
	$txt = str_replace('{{address_link}}', 'https://shippingdog.com/order/address?id=' . $cart_id . '&key=' . $security, $txt);
	$txt = str_replace('{{orders}}', $orders, $txt);
	$txt = str_replace('{{order_link}}', 'https://shippingdog.com/order?id=' . $cart_id . '&user_id=' . $user_id, $txt);

	$email->SMTPDebug = 1;
	$email->isSMTP();
	$email->Host = 'email.shippingdog.com';
	$email->SMTPAuth = true;
	$email->Username = 'noreply@shippingdog.com';
	$email->Password = 'REDACTED_SMTP_PASSWORD';
	$email->SMTPSecure = 'ssl';
	$email->Port = 465;

	$email->setFrom('noreply@shippingdog.com', 'Shipping Dog');
	$email->addAddress($toemail);
	$email->addReplyTo(sys_config('contact-email'), 'Shipping Dog Info');

	$email->isHTML(true);
	$email->Subject = 'Conferma ordine';
	$email->Body = $txt;

	$email->Send();
}
