<?php

function send_email($email, $cartID) {

	//generate email text
	$buyer = DB::find('buyer FROM carts WHERE ID = ?', [$cartID]);

	$row_addr = DB::row('* FROM addresses WHERE cartID = ?', [$cartID]);

	$addr_aname = $row_addr['aname'];
	$addr_strt = $row_addr['address'];
	$addr_num = $row_addr['number'];
	$addr_zip = $row_addr['zip'];
	$addr_city = $row_addr['city'];
	$addr_reg = $row_addr['region'];

	$security = $row_addr['security'];
	$toemail = $row_addr['email'];

	$orders = '';
	$res_ord = DB::select('ID FROM orders WHERE cartID = ? ORDER BY ID ASC', [$cartID]);

	while ($row_ord = $res_ord->fetch_assoc()) {

		$order = new Order($row_ord['ID']);

		$orders .= $order->desc['normal'] . '<br>';
	}

	$address =
		$addr_aname . '<br>' .
		$addr_strt . ', ' . $addr_num . '<br>' .
		$addr_zip . ' ' . $addr_city . ' (' . $addr_reg . ')';

	$txt = file_get_contents(SERVER_ROOT . '/app/functions/email/order_confirmation.html');

	$txt = str_replace('{{email}}', sys_config('contact-email'), $txt);
	$txt = str_replace('{{cartID}}', $cartID, $txt);
	$txt = str_replace('{{address}}', $address, $txt);
	$txt = str_replace('{{address_link}}', 'https://shippingdog.com/order/address?id=' . $cartID . '&key=' . $security, $txt);
	$txt = str_replace('{{orders}}', $orders, $txt);
	$txt = str_replace('{{order_link}}', 'https://shippingdog.com/order?id=' . $cartID . '&buyer=' . $buyer, $txt);

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
