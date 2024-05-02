<?php

use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Order;
use Legacy\Classes\Ecommerce\OrderItem as Cart;

?>

{% block main %}

<p id="log" class="local">
	<?php if ($res_sel->num_rows) {
		while ($row = $res_sel->fetch_assoc()) {

			$order = new Cart($row['id']);
			$newOrder = EcommerceOrder::find($order->id);
			$user_id = $row['user_id'];
			$shipping_method_id = $row['shipping_method_id'];
			$reg = (strpos($user_id, 'T') !== false) ? false : true;
			$res_str = $reg ? ' - TRACKING' : '';
			$stat = $row['status'];
			$err = $row['error'];

			// $pay = $row['payment_method_id'] == 1 ? 'PayPal' : 'Altro pagamento';

			if ($order->addressId) {
				$address =
					$order->address->fullAddress . '<br>'
					. $order->address->email . '<br>'
					. $order->address->phone;
			} else {
				$address = "<br><span style='color:red'>!!! Errore nell'indirizzo !!!</span>";

				// Set the cart as having errors
				$newOrder->has_errors = 1;
				$newOrder->save();

				// Get the PayPal data
				$paypalData = paypal_find_order($order->id);

				if ($paypalData) {

					// Create the address
					$addressLine1 = explode(" ", $paypalData->shipping_info->address->line1);
					$addressNumber = array_pop($addressLine1);
					$addressLine1 = implode(" ", $addressLine1);

					Address::create([
						'order_id' => $order->id,
						'user_id' => $reg ? $order->user_id : null,
						'full_name' => $paypalData->shipping_info->name,
						'address' => $addressLine1,
						'number' => $addressNumber,
						'city' => $paypalData->shipping_info->address->city,
						'zip' => $paypalData->shipping_info->address->postal_code,
						'country' => $paypalData->shipping_info->address->country_code,
						'email' => $paypalData->payer_info->email_address,
						'phone' => $paypalData->payer_info->phone_number->national_number,
					]);
				}
			}

			$code_str = empty($row['code']) ? '' : '<br><br>Sconto: ' . $row['code'];
			$name_str = \Legacy\System\Classes\LegacyDB::exists('users WHERE id = ? AND level = 1', [$user_id]) ? ' *' : '';

			$ord_str = '';

			$res_ord = \Legacy\System\Classes\LegacyDB::select('id FROM order_items WHERE order_id = ? ORDER BY time ASC', [$order->id]);

			// if ($newOrder->has_errors) {
			// 	$paypalOrderIds = [];
			// 	$paypalData = paypal_find_order($order->id);

			// 	$paypalOrderIds = array_column($paypalData->order_info->item_details, 'item_code');

			// 	$priceComparison = $paypalData->transaction_info->transaction_amount->value;
			// }

			foreach ($order->order_items as $orderItem) {
				$validOrder = true;

				// if ($newOrder->has_errors) {
				// 	if (!in_array($orderItem->id, $paypalOrderIds)) {
				// 		$validOrder = false;

				// 		OrderItem::where('id', $orderItem->id)->delete();
				// 	}
				// }

				if ($validOrder) {
					if ($orderItem->inventory == 1) $ord_str .= '<span style="color: lightgreen; font-weight: bold;">! IN INVENTARIO ! </span>';
					if ($orderItem->inventory == 2) $ord_str .= '<span style="color: green; font-weight: bold;">! SIMILE IN INVENTARIO ! </span>';

					$ord_str .= '<a href="' . $orderItem->product->fullUrl . '" target="_blank">' . $orderItem->design_id . ' - ' . $orderItem->getDescription(true) . '</a><br>';
				}
			}

			echo '[' . date('Y-m-d H:i:s', strtotime($row['time'])) . ' - ' . $order->id . ']' . $name_str . '<br>'
				. $address
				. $code_str . '<br><br>'
				. $ord_str . '<br>'
				. 'Spedizione: ' . $order->shippingMethod->shortName . ', ' . $order->shippingMethod->deliveryDateString . '<br>';

			if (!$newOrder->change_request) {
				echo 'Totale: ' . $order->priceString . '<br>';
			} else {
				echo "<span style='color: CornflowerBlue; font-weight: bold;'>CAMBIO TAGLIA</span><br>";
			}

			echo '-------|<br><br>';
	?>
		<?php }
	} else { ?>
		Nessun ordine registrato dopo il <?= (new DateTime($date))->format('d/m/Y') ?>
	<?php } ?>
</p>

<div class="mar-b-2 center-content">
	<a href="addresses_clean?id=log_date" class="btn button--auto-width">
		<?= $res_sel->num_rows ? 'Pulisci log' : 'Aggiorna la data<br>dell\'ultimo ordine fatto' ?>
	</a>
</div>

{% end main %}