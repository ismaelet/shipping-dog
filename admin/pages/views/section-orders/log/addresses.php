<?php

use App\Models\Ecommerce\Address;
use App\Models\Ecommerce\Cart as EcommerceCart;
use Ecommerce\Cart as Cart;

?>

{% block main %}

<p id="log" class="local">
	<?php if ($res_sel->num_rows) {
		while ($row = $res_sel->fetch_assoc()) {

			$cart = new Cart($row['ID']);
			$newCart = EcommerceCart::find($cart->id);
			$buyer = $row['buyer'];
			$shipID = $row['shipID'];
			$reg = (strpos($buyer, 'T') !== false) ? false : true;
			$res_str = $reg ? ' - TRACKING' : '';
			$stat = $row['status'];
			$err = $row['error'];

			// $pay = $row['payID'] == 1 ? 'PayPal' : 'Altro pagamento';

			if ($cart->addressId) {
				$address =
					$cart->address->fullAddress . '<br>'
					. $cart->address->email . '<br>'
					. $cart->address->phone;
			} else {
				$address = "<br><span style='color:red'>!!! Errore nell'indirizzo !!!</span>";

				// Set the cart as having errors
				$newCart->has_errors = 1;
				$newCart->save();

				// Get the PayPal data
				$paypalData = paypal_find_order($cart->id);

				if ($paypalData) {

					// Create the address
					$addressLine1 = explode(" ", $paypalData->shipping_info->address->line1);
					$addressNumber = array_pop($addressLine1);
					$addressLine1 = implode(" ", $addressLine1);

					Address::create([
						'cartID' => $cart->id,
						'buyer' => $reg ? $cart->buyer : null,
						'aname' => $paypalData->shipping_info->name,
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
			$name_str = DB::exists('users WHERE ID = ? AND level = 1', [$buyer]) ? ' *' : '';

			$ord_str = '';

			$res_ord = DB::select('ID FROM orders WHERE cartID = ? ORDER BY time ASC', [$cart->id]);

			// if ($newCart->has_errors) {
			// 	$paypalOrderIds = [];
			// 	$paypalData = paypal_find_order($cart->id);

			// 	$paypalOrderIds = array_column($paypalData->cart_info->item_details, 'item_code');

			// 	$priceComparison = $paypalData->transaction_info->transaction_amount->value;
			// }

			foreach ($cart->orders as $order) {
				$validOrder = true;

				// if ($newCart->has_errors) {
				// 	if (!in_array($order->id, $paypalOrderIds)) {
				// 		$validOrder = false;

				// 		Order::where('ID', $order->id)->delete();
				// 	}
				// }

				if ($validOrder) {
					if ($order->inventory == 1) $ord_str .= '<span style="color: lightgreen; font-weight: bold;">! IN INVENTARIO ! </span>';
					if ($order->inventory == 2) $ord_str .= '<span style="color: green; font-weight: bold;">! SIMILE IN INVENTARIO ! </span>';

					$ord_str .= '<a href="' . $order->product->fullUrl . '" target="_blank">' . $order->designId . ' - ' . $order->getDescription(true) . '</a><br>';
				}
			}

			echo '[' . date('Y-m-d H:i:s', strtotime($row['time'])) . ' - ' . $cart->id . ']' . $name_str . '<br>'
				. $address
				. $code_str . '<br><br>'
				. $ord_str . '<br>'
				. 'Spedizione: ' . $cart->shippingMethod->shortName . ', ' . $cart->shippingMethod->deliveryDateString . '<br>';

			if (!$newCart->change_request) {
				echo 'Totale: ' . $cart->priceString . '<br>';
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
	<a href="addresses_clean?id=log_date" class="button button--auto-width">
		<?= $res_sel->num_rows ? 'Pulisci log' : 'Aggiorna la data<br>dell\'ultimo ordine fatto' ?>
	</a>
</div>

{% end main %}