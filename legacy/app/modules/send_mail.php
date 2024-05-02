<?php

/* OLD
function send_email($email, $order_id) {

	//generate email text
	$user_id = \Legacy\System\Classes\LegacyDB::find('user_id FROM orders WHERE id = ?', [$order_id]);

	$row_addr = \Legacy\System\Classes\LegacyDB::row('* FROM addresses WHERE order_id = ?', [$order_id]);

	$addr_full_name = $row_addr['full_name'];
	$addr_strt = $row_addr['address'];
	$addr_num = $row_addr['number'];
	$addr_zip = $row_addr['zip'];
	$addr_city = $row_addr['city'];
	$addr_reg = $row_addr['region'];

	$security = $row_addr['security'];
	$toemail = $row_addr['email'];

	$order_items = '';
	$res_ord = \Legacy\System\Classes\LegacyDB::select('id FROM order_items WHERE order_id = ? ORDER BY id ASC', [$order_id]);

	while ($row_ord = $res_ord->fetch_assoc()) {

		$orderItem = new Order($row_ord['id'], '*');

		$order_items .= $orderItem->desc[0] . '<br>';
	}

	$address =
		$addr_full_name . '<br>' .
		$addr_strt . ', ' . $addr_num . '<br>' .
		$addr_zip . ' ' . $addr_city . ' (' . $addr_reg . ')';

	$txt = file_get_contents(SERVER_ROOT . '/app/modules/email/order_confirmation.html');

	$txt = str_replace('{{order_id}}', $order_id, $txt);
	$txt = str_replace('{{address}}', $address, $txt);
	$txt = str_replace('{{address_link}}', 'https://shippingdog.com/order/address?id=' . $order_id . '&key=' . $security, $txt);
	$txt = str_replace('{{order_items}}', $order_items, $txt);
	$txt = str_replace('{{order_link}}', 'https://shippingdog.com/order?id=' . $order_id . '&user_id=' . $user_id, $txt);

	$email->SMTPDebug = 1;
	$email->isSMTP();
	$email->Host = 'email.shippingdog.com';
	$email->SMTPAuth = true;
	$email->Username = 'noreply@shippingdog.com';
	$email->Password = 'REDACTED_SMTP_PASSWORD';
	$email->SMTPSecure = 'ssl';
	$email->Port = 465;

	$email->setFrom('noreply@shippingdog.com', 'Shipping Dog SNC');
	$email->addAddress($toemail);
	$email->addReplyTo('', 'Shipping Dog Info');

	$email->isHTML(true);
	$email->Subject = 'Conferma ordine';
	$email->Body = $txt;

	$email->Send();
}
*/