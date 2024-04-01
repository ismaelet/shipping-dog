<?php
?>

{% block main %}

<div id="slider">
	<div class="bgr"></div>
	<h1 id="slider-title">CA<span class="txt-c1">RR</span>ELLO</h1>
</div>

<div id="body">

	<div class="main-wrap">

		<!-- Flow bar -->
		<?php

		use Ecommerce\Product;

		cart_flow($cart, $loc) ?>

		<section id="offer-sec" class="mar-b-3">
			<h1 class="title">Offerte speciali</h1>

			<?php if (false) { ?>
				<div id="offer-panel" class="br-c4">
					<form method="POST" id="offer-grid" class="grid">
						<div class="offer-img-box grid-ele txt-cen pad-v-1">
							<div id="offer-img" class="inlb"></div>
						</div>

						<div class="offer-data grid-ele">
							<h1 class="offer-name txt-bold txt-15rem">Aggiungi un articolo a sorpresa</h1>

							<p class="offer-desc mar-t-1">Aggiungi un articolo a sorpresa per un prezzo molto ridotto. Potrai scegliere taglia, modello e quanto vuoi pagare, ma il design e capo sarà a sorpresa e scelto da noi.</p>
						</div>

						<div class="offer-add grid-ele txt-cen">
							<div id="offer-add-img" class="inlb clickable"></div>
							<h1 id="offer-add-price" class="txt-bold">3€ - 8€</h1>
						</div>
					</form>
				</div>

				<div id="offer-lbox-over" class="lbox-over"></div>

				<div id="offer-lbox" class="lbox">

					<form id="offer-lbox-form" class="lbox-form" method="POST">

						<h1 class="lbox-form-title">Aggiungi un articolo a sorpresa</h1>
						<p class="txt">Seleziona una delle possibilità qui sotto. Potrai scegliere modello e taglia.</p>
						<div id="offer-lbox-list" class="mar-t-1">
							<?php for ($i = 0; $i < 3; $i++) {
								$off_txt = array(
									'Selezionando questa opzione riceverai una t-shirt normale o una t-shirt con scollo a V o una canotta. È possibile ottenere la maglietta con scollo a V solo selezionando il modello <span class="txt-bold txt-c-title">donna</span>.',
									'Selezionando questa opzione riceverai una felpa senza cappuccio,',
									'Selezionando questa opzione riceverai una felpa con cappuccio con o senza zip, oppure uno zaino.',
								) ?>

								<div class="offer-lbox-row grid clickable">
									<div class="grid-ele">
										<div class="offer-lbox-img img cont" style="background-image: url(<?= file_time('/app/assets/assets/cart/offer' . ($i + 1) . '.png') ?>);"></div>
									</div>
									<div class="grid-ele">
										<p class="offer-lbox-data">
											<?= $off_txt[$i] ?>
										</p>
									</div>
									<div class="grid-ele">
										<div class="offer-lbox-price">
											<h1>3€</h1>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>

					</form>

				</div>

			<?php } else { ?>

				<section id="official-products">

					<p class="mar-b-1">Non lasciarti sfuggire i nostri <span class="txt-bold txt-c-title">prodotti invernali</span>, perfetti per le giornate gelide. Ora disponibili ad un prezzo estremamente <span class="txt-bold txt-c-title">scontato</span>. Se non vuoi acquistarne nessuno clicca <span class="txt-bold txt-c-title">PROSEGUI</span> qui in basso.</p>

					<div class="grid4 grid3-1024w grid2-768w gap2">

						<?php

						$products = [[75581, 1], [75581, 13], [75581, 5], [75581, 14]];

						foreach ($products as $productData) {
							$product = (new Product($productData[0]))->setColorId($productData[1]);
						?>

							<div class="item-bomx">

								<div class="item">
									<a href="<?= $product->fullUrl ?>" class="img block">
										<img src="<?= client_path($product->image) ?>" alt="" class="img-fill">
									</a>

									<div class="grid content">

										<div class="">
											<h3 class="txt-1rem txt-med txt-line"><?= $product->name . ' - ' . $product->color->name ?></h3>
											<p class="txt-1rem mar-t-05 txt-sbold"><?= str_replace('.', ',', $product->price) ?>€</p>
										</div>

										<div class="lbox-open center-items clickable" data-tar="#lbox-<?= $product->id . $product->color->id ?>">
											<div class="icon-box">
												<?= icon_embed('ecommerce/add-to-cart') ?>
											</div>
										</div>

									</div>
								</div>

								<div id="lbox-<?= $product->id . $product->color->id ?>" class="lbox">

									<form method="POST" class="form grid2 grid1-768w">

										<input type="hidden" name="id" value="<?= $product->id ?>">
										<input type="hidden" name="size" value="13">
										<input type="hidden" name="color" value="<?= $product->color->id ?>">

										<img src="<?= client_path($product->image) ?>" class="img-fill">

										<div class="content pad-3">

											<h3 class="txt-2rem txt-sbold mar-b-1"><?= $product->name ?></h3>
											<h3 class="txt-15rem txt-sbold mar-b-2"><?= $product->price ?> €</h3>
											<p><?= $product->description ?></p>

											<a href="<?= $product->clientUrl ?>">
												<h3 class="mar-t-1 txt-c2">Esplora tutti i colori</h3>
											</a>

											<div class="add-to-cart-box grid2 mar-t-2">
												<input type="number" name="quantity" value="1" min="1" max="999" step="1" class="inp">

												<button type="submit" name="add-to-cart" class="pbtn mar-t-2">
													Aggiungi al carrello
												</button>
											</div>

										</div>

									</form>

								</div>

							</div>

						<?php } ?>

					</div>

					<a href="<?= $product->clientUrl ?>" class="txt-cen mar-t-2 block hidden">
						<p class="inlb brb1 br-2 br-c1-h pad-h-1">Vedi tutti i colori disponibili</p>
					</a>

				</section>

			<?php } ?>

		</section>

		<!-- Recap -->
		<form method="POST">

			<?php cart_recap($cart, true) ?>

			<button type="submit" name="submit" id="submit" class="hidden"></button>

		</form>

	</div>
</div>

{% end main %}