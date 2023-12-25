<?php

use Ecommerce\Cart as Cart;

?>

{% block main %}

<p id="log">
	<?php if ($res_sel->num_rows) {
		while ($row = $res_sel->fetch_assoc()) {

			$cart = new Cart($row['ID']);
			$buyer = $row['buyer'];
			$shipID = $row['shipID'];
			$reg = (strpos($buyer, 'T') !== false) ? false : true;
			$res_str = $reg ? ' - TRACKING' : '';
			$stat = $row['status'];
			$err = $row['error'];

			$pay = $row['payID'] == 1 ? 'PayPal' : 'Stringipe';

			if ($cart->addressId) {
				$address =
					$cart->address->fullAddress . '<br>'
					. $cart->address->email . '<br>'
					. $cart->address->phone;
			} else $address = "<br><span style='color:red'>!!! Errore nell'indirizzo !!!</span>";

			$code_str = empty($row['code']) ? '' : '<br><br>Sconto: ' . $row['code'];
			$name_str = DB::exists('users WHERE ID = ? AND level = 1', [$buyer]) ? ' *' : '';

			$ord_str = '';

			$res_ord = DB::select('ID FROM orders WHERE cartID = ? ORDER BY time ASC', [$cart->id]);

			foreach ($cart->orders as $order) {
				if ($order->inventory == 1) $ord_str .= '!! IN INVENTARIO !! ';
				if ($order->inventory == 2) $ord_str .= '!! SIMILE IN INVENTARIO !! ';

				$ord_str .= '<a href="' . $order->product->fullUrl . '">' . $order->designId . ' - ' . $order->description . '</a><br>';
			}

			echo '[' . date('Y/m/d', strtotime($row['time'])) . ' - ' . $pay . ' - ' . $cart->id . ']' . $name_str . '<br>'
				. $address
				. $code_str . '<br><br>'
				. $ord_str . '<br>'
				. 'Spedizione: ' . $cart->shippingMethod->shortName . ', ' . $cart->shippingMethod->deliveryDateString . '<br>'
				. 'Totale: ' . $cart->priceString . '<br>'
				. '-------|<br><br>';
	?>
		<?php }
	} else { ?>
		Nessun ordine registrato dopo il <?= (new DateTime($date))->format('d/m/Y') ?>
	<?php } ?>
</p>

<div class="mar-b-2 center-content">
	<a href="addresses_clean?id=log_date" class="button"><?= $res_sel->num_rows ? 'Pulisci log' : 'Aggiorna la data<br>dell\'ultimo ordine fatto' ?></a>
</div>

{% end main %}