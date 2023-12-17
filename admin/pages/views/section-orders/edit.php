{% block page-body %}

<!-- Main content -->
<div class="main-content grid-flow" data-spacing="700">

	<!-- Orders -->
	<section class="form">
		<h2 class="form-title">Dati dell'ordine</h2>

		<div class="panel">
			<div class="flex-group" data-spacing="400">
				<div class="status-icon icon-box" style="--_status-color: <?= $cart->statusColor ?>">
					<?= icon_embed($cart->statusIcon) ?>
				</div>

				<div>
					<p class="status__label"><?= $cart->adminStatusName ?></p>
					<p class="">Ordinato il <?= $cart->dateString ?></p>
				</div>
			</div>

			<?php /*if ($cart->comment && $cart->status == -1) { ?>
				<p class="mar-t-1"><?= $cart->comment ?></p>
			<?php } */ ?>


			<div class="orders-header mar-t-2">
				<h3>Prodotto</h3>
				<h3>Prezzo</h3>
			</div>

			<!-- Orders list -->
			<div class="orders <?= $cart->status ? 'orders_completed' : '' ?>">

				<?php foreach ($cart->orders as $order) { ?>

					<a class="order" href="<?= $order->product->clientUrl ?>">
						<img src="<?= $order->product->clientImage ?>" alt="" class="order-img img-fill">
						<p class="order-name"><?= $order->description ?></p>
						<p class="order-price"><?= $order->fullPriceString ?></p>
					</a>

				<?php } ?>

			</div>

			<!-- Recap -->
			<div class="recap">
				<?php if ($cart->couponId) { ?>
					<div class="recap-row">
						<h3 class="recap-text">Codice</h3>
						<p class="recap-price"><?= $cart->coupon->code ?></p>
					</div>
				<?php } ?>

				<?php if ($cart->discount) { ?>
					<div class="recap-row">
						<h3 class="recap-text">Sconto</h3>
						<p class="recap-price">-<?= $cart->discountString ?></p>
					</div>
				<?php } ?>

				<div class="recap-row">
					<h3 class="recap-text">Spedizione</h3>
					<p class="recap-price"><?= $cart->shippingPriceString ?></p>
				</div>

				<div class="recap-row recap-row_total">
					<h3 class="recap-text">Totale</h3>
					<p class="recap-price"><?= $cart->priceString ?></p>
				</div>
			</div>
		</div>
	</section>
	<section class="cart-actions flex-group">

		<?php if ($cart->statusId > 0) { ?>

			<button type="button" class="cart-action cart-action_cancel button button--secondary button--auto-width button--bad" data-function="open-modal" data-target="cancel-order-modal" name="change-status" value="canceled">
				<?= icon_embed('close') ?>
				Cancella l'ordine
			</button>

		<?php } ?>

		<?php if ($cart->statusId == -1) { ?>

			<button type="submit" class="cart-action cart-action_restore button button--auto-width" name="change-status" value="received">
				<?= icon_embed('correct') ?>
				Ripristina l'ordine
			</button>

			<button type="submit" class="cart-action cart-action_restore button button--secondary button--auto-width" name="change-status" value="open">
				<?= icon_embed('correct') ?>
				Riapri il carrello
			</button>

		<?php } else if (!$cart->statusId) { ?>

			<button type="submit" class="cart-action cart-action_restore button button--auto-width" name="change-status" value="received">
				<?= icon_embed('correct') ?>
				Conferma l'ordine
			</button>

		<?php } else if ($cart->statusId == 1) { ?>

			<button type="submit" class="cart-action cart-action_accept button button--auto-width" name="change-status" value="label-ready">
				<?= icon_embed('correct') ?>
				Etichetta pronta
			</button>

		<?php } else if ($cart->statusId == 2) { ?>

			<button type="submit" class="cart-action cart-action_send button button--auto-width" name="change-status" value="shipped">
				<?= icon_embed('correct') ?>
				Spedito
			</button>

		<?php } else if ($cart->statusId == 3) { ?>

			<button type="submit" class="cart-action cart-action_delivered button button--auto-width" name="change-status" value="delivered">
				<?= icon_embed('correct') ?>
				Consegnato
			</button>

		<?php } ?>

	</section>
</div>

<!-- Side bar -->
<aside class="side-bar">

	<section class="form">
		<h3 class="form-title">Dati del cliente</h3>

		<div class="panel">
			<div class="form-inputs">
				<div class="form-field">
					<p class="lbl">Nome completo</p>
					<p><?= $cart->address->fullname ?></p>
				</div>
				<div class="form-field">
					<p class="lbl">Contatti</p>
					<p>
						<a class="link" href="tel:<?= $cart->address->phone ?>"><?= $cart->address->phone ?></a><br>
						<a class="link" href="emailto:<?= $cart->address->email ?>"><?= $cart->address->email ?></a>
					</p>
				</div>
			</div>
		</div>
	</section>

	<section class="form">
		<h3 class="form-title">Destinatario</h3>

		<div class="panel">
			<p class="txt-med txt-lh2"><?= $cart->address->fullAddress ?></p>
			<p class="txt-med txt-lh2"><?= $cart->notes ?: '' ?></p>

			<?php if ($cart->status > 1) { ?>

				<div class="tracking mar-t-1">
					<input type="text" class="inp mar-t-1" name="tracking" value="<?= $cart->tracking ?>" placeholder="Codice di tracking">
					<button type="submit" class="button mar-t-1 hidden" name="update-tracking">Salva</button>
				</div>

			<?php } ?>
		</div>

	</section>
</aside>

<!-- Loading panel -->
<div class="loading">
	<div class="load-icon place-cen"></div>
</div>

<!-- Cancel order modal -->
<dialog class="modal" id="cancel-order-modal">
	<div class="modal-form" method="POST">

		<div class="modal-form-header">
			<h2>Cancella l'ordine</h2>
		</div>

		<div class="modal-form-body">
			<p class="mar-b-05">Qui è possibile aggiungere un commento per spiegare perché l'ordine è stato cancellato.</p>
			<textarea class="textarea" name="comment" placeholder="Commento (opzionale)"></textarea>
		</div>

		<div class="modal-form-footer">
			<div class="sbtn" data-function="close-modal">Indietro</div>
			<button type="submit" class="button" name="change-status" value="canceled" data-style="warning">Cancella l'ordine</button>
		</div>
	</div>
</dialog>

{% end page-body %}