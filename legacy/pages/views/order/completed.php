<?php

use Ecommerce\ViewManager;

?>

{% block main %}

<div id="slider">
	<div class="bgr"></div>
	<h1 id="slider-title">ORDINE <span class="txt-c1"><?= $cart->idCode() ?></span></h1>
</div>

<div id="wrap" class="main-wrap pad-b-4">
	<h2 id="title" class="txt-2rem background-status-color--<?= $cart->statusCode() ?>"><?= $cart->statusName() ?></h2>

	<p id="head_txt" class="pad-v-2">
		Il tuo ordine verrà spedito entro <strong>1-5 giorni lavorativi</strong> dal momento del pagamento. Riceverai una email dal <strong>corriere GLS</strong> con codice e istruzioni di <strong>tracciamento</strong> intorno alle <strong>20:00</strong> del giorno di spedizione. Abbiamo mandato una email di conferma al tuo indirizzo email. Nel caso non appaia entro 5 minuti dal momento del pagamento, consigliamo di controllare la <strong>cartella di spam</strong>.
	</p>

	<div id="content" class="grid al-top">
		<div id="left_panel" class="panel">

			<!-- Shipping -->
			<section id="ship_sect" class="section">
				<h3 class="title">Spedizione</h3>

				<div id="ship_cont">
					<div class="data_col" style="width: 25%;"></div>
					<div class="data_col" style="width: 75%;"></div>

					<label class="ship_row">
						<div class="ship_chck_box">
							<input type="checkbox" class="inp_cbx ship_cbx" checked disabled>

							<div class="icon">
								<div class="icon-dot"></div>
							</div>
						</div>

						<div class="ship_box">
							<h1 class="art_name">
								<?= $cart->shipping_method->name ?><br>
								<span class="date"><?= $cart->shipping_method->deliveryDateString ?></span>
							</h1>

							<p class="order-list__description"><?= $cart->shipping_method->description ?></p>
						</div>
					</label>
				</div>
			</section>

			<!-- Address -->
			<section id="addr_sect" class="section">
				<h2 class="title">Indirizzo</h2>

				<div id="addr_cont">
					<?= $cart->address->fullAddress() ?? '' ?><br>
					-<br>
					<?= $cart->address->email ?? '' ?><br>
					<?= $cart->address->phone ?? '' ?>
					</p>

					<?php if ($cart->status == 1) { ?>

						<a href="<?= client_url('/order/address?id=' . $cart->id . '&key=' . $cart->address->secretNumber) ?>">
							<p class="edit-address">Modifica l'indirizzo</p>
						</a>

					<?php } ?>

				</div>
			</section>
		</div>

		<div id="right_panel" class="panel">

			<!-- Orders -->
			<?= ViewManager::renderOrderList($cart) ?>

			<!-- Recap -->
			<?php cart_recap($cart, false) ?>

		</div>

	</div>
</div>

{% end main %}