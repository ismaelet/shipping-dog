{% block content %}

<div class="grid-flow" data-align='top' data-columns="30rem" data-spacing="700">

	<!-- Labels count -->
	<section class="panel">
		<div class="panel__header">
			<h3 class="panel__title">Etichette da preparare</h3>
		</div>

		<div class="panel__body">
			<p class="panel__counter"><?= $lblN ?></p>
		</div>

		<div class="panel__footer">
			<a href="<?= client_url('/shipping/courier') ?>" class="button">Genera le etichette</a>
		</div>
	</section>

	<!-- Pending sellers -->
	<form class="panel" method="POST">
		<div class="panel__header">
			<h3 class="panel__title">Negozi da approvare</h3>
		</div>

		<div class="panel__body grid-flow" data-spacing="200">

			<?php

			use Ecommerce\Seller;

			if ($sellers->num_rows) {
				while ($sellerEntry = $sellers->fetch_assoc()) {
					$seller = new Seller($sellerEntry[Seller::INDEX]);
			?>

					<div class="seller-item grid-flow" data-spacing="200">
						<p class="seller-item__title"><?= $seller->name ?></p>

						<div class="flex-group" data-column-spacing="400">
							<a href="<?= $seller->clientUrl ?>" class="seller-item__button">Visualizza</a>
							<button type="submit" class="seller-item__button seller-item_button--green" name="accept-seller" value="<?= $seller->id ?>">Accetta</button>
							<button type="submit" class="seller-item__button seller-item_button--red" name="delete-seller" value="<?= $seller->id ?>" onclick="return confirm('Sei sicuro di voler eliminare il negozio?')">Elimina</button>
						</div>
					</div>

				<?php }
			} else { ?>

				<div class="empty">
					<p class="empty">Nessun negozio da approvare</p>
				</div>

			<?php } ?>

		</div>
	</form>
</div>

{% end content %}