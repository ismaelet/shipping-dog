{% block main %}

<p id="log">

	/// <?= $ordersN ?> ARTICOLI DA FARE<br>

	<?php if ($ordersN) { ?>

		/// A PARTIRE DA ORDINE #<?= $firstID . ' ' . (empty($first['aname']) ? '' :  ' (' . $first['aname'] . ')') ?>

		<br><br><br>

	<?php
		foreach ($logEntries as $sellerName => $designs) {
			ksort($designs);

			echo '[[ ' . $sellerName . ' ]]<br>';

			foreach ($designs as $designName => $orders) {
				echo '- ' . $designName . '<br>';

				foreach ($orders as $orderData => $orderQuantity) {
					echo $orderQuantity . ' x ' . $orderData . '<br>';
				}

				echo '<br>';
			}
		}
	} ?>

</p>

<form method="POST" class="flex-group center-content mar-t-2">
	<button type="submit" name="submit" class="button" onclick="return confirm('Sei sicuro di voler pulire il log?')">Pulisci log</button>
</form>

{% end main %}