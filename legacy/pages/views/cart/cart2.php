<?php

use App\View\Components\Form\Input;
use Ecommerce\ViewManager;
use ViewManager\Ecommerce\CartFlowBar;
use ViewManager\Ecommerce\CartRecap;



?>

{% block main %}

<div id="slider">
	<div class="bgr"></div>
	<h1 id="slider-title">CA<span class="txt-c1">RR</span>ELLO</h1>
</div>

<form id="body" method="POST">
	<div class="main-wrap">

		<?= CartFlowBar::render() ?>

		<?php if ($warning) { ?>

			<p id="news" class="<?= $warning['color'] ?>"><?= $warning['text'] ?></p>

		<?php } ?>

		<div id="content" class="grid">
			<div method="POST" id="left_panel" class="panel">

				<!-- Country -->
				<section id="ship_sect" class="section">
					<h3 class="title">Metodo di spedizione</h3>

					<p class="bott_txt">
						<?php if (cart_old()->longDeliveryTime) { ?>
							Il tuo ordine contiene un capo in <span class="txt-red txt-bold">preordine</span>. Se vuoi un <strong>tempo di consegna minore</strong>, ti consigliamo di selezionare solo magliette, felpe o accessori e con colore bianco, nero o grigio melange.<br>Se invece ti va bene la data di consegna indicata qui sotto, puoi lasciare l'ordine così com'è.<br><br>
						<?php } ?>

						La data di consegna prevista comprende, oltre al tempo di spedizione, <strong>1-5 giorni lavorativi</strong> di elaborazione dell'ordine.
					</p>

					<select id="count_sel" name="countID" class="sel" onChange="if ($(this).val() !== '0') {$('#country').click();}">
						<option value="0" <?php if (!cart_old()->countryId) echo 'selected' ?>>Seleziona:</option>

						<?php while ($country = $countries->fetch_assoc()) { ?>

							<option value="<?= $country['ID'] ?>" <?php if (cart_old()->countryId == $country['ID']) echo 'selected' ?>><?= $country['name_it'] ?></option>

						<?php } ?>

					</select>

					<button type="submit" id="country" name="country" class="hidden" formnovalidate></button>
				</section>

				<!-- Shipping method -->
				<section id="shipping" class="">
					<div id="ship_cont">

						<?php foreach ($shippingMethods as $shippingMethod) {
							$shippingMethod->setAssociatedCart(cart_old());
						?>

							<label class="ship_row interactive">
								<div class="checkbox">
									<input type="radio" class="inp_cbx ship_cbx js-shipping-method js-cart-shipping-price-string" name="cbx_ship" value="<?= $shippingMethod->id ?>" <?= cart_old()->shippingMethodId == $shippingMethod->id ? 'checked' : '' ?> data-shipped="<?= $shippingMethod->shipped ? 1 : 0 ?>" data-cart-id="<?= cart_old()->id ?>" data-shipping-method-id="<?= $shippingMethod->id ?>" required>

									<span></span>
								</div>

								<div class="ship_box">
									<h3 class="art_name">
										<?= $shippingMethod->name ?><br>
										<span class="date"><?= $shippingMethod->deliveryDateString ?></span>
									</h3>
									<p class="order-list__description"><?= $shippingMethod->description ?></p>
									<h3 class="price"><?= $shippingMethod->priceString ?></h3>
								</div>
							</label>

							<?php

							if (cart_old()->shippingMethodId == $shippingMethod->id) {

								if ($shippingMethod->price && !cart_old()->couponId && !cart_old()->shippingWithCredit && cart_old()->usableCredit >= $shippingMethod->price) { ?>

									<button class="use-credit-btn credit-btn btn btn--secondary button--full-width mar-b-2" name="ship_credit">
										<?= embed_icon('dollar') ?>
										Usa il tuo credito
									</button>

								<?php } else if (cart_old()->shippingWithCredit) { ?>

									<button class="no-credit-btn credit-btn btn btn--secondary button--full-width mar-b-2" name="ship_no_credit">
										<?= embed_icon('close') ?>
										Non usare il credito
									</button>

								<?php } ?>

						<?php }
						} ?>

					</div>
				</section>

				<!-- Shipping address -->
				<section id="address" class="address-form <?= cart_old()->localDelivery ? 'address-form--local-delivery' : '' ?>">
					<h3 class="title">Indirizzo</h3>

					<div class="grid gap1">

						<?= Input::render(['name' => 'aname', 'placeholder' => 'Nominativo di spedizione', 'value' => $address->fullname, 'required' => true, 'trim' => true]) ?>

						<div class="field">
							<input type="text" id="inp_addr" class="input delivery-input" name="address" placeholder="Indirizzo" value="<?= $address->address ?>" maxlength="30" autocomplete="address-line1" <?= !cart_old()->localDelivery ? 'required' : '' ?>>
							<input type="text" id="inp_num" class="input delivery-input" name="number" placeholder="Numero civico" value="<?= $address->number ?>" maxlength="10" <?= !cart_old()->localDelivery ? 'required' : '' ?>>
						</div>

						<input type="text" class="input delivery-input" name="city" placeholder="Città" value="<?= $address->city ?>" maxlength="30" autocomplete="address-level2" <?= !cart_old()->localDelivery ? 'required' : '' ?>>

						<div class="field">
							<input type="text" class="input delivery-input" name="zip" placeholder="Codice postale" value="<?= trim($address->zip) ?>" maxlength="5" autocomplete="postal-code" <?= !cart_old()->localDelivery ? 'required' : '' ?>>
							<input type="text" class="input delivery-input" name="region" placeholder="Provincia" value="<?= $address->region ?>" maxlength="2" autocomplete="address-level1" <?= !cart_old()->localDelivery ? 'required' : '' ?>>
						</div>

						<input type="email" class="input" name="email" placeholder="Indirizzo e-mail" value="<?= $address->email ?>" maxlength="50" required autocomplete="email">
						<input type="text" class="input js-input-no-space" name="phone" placeholder="Numero di telefono" value="<?= $address->phone ?>" maxlength="30" autocomplete="tel" required>

					</div>

					<button type="submit" id="submit" name="submit" class="hidden"></button>
				</section>
			</div>

			<div id="right_panel" class="panel">

				<!-- Orders -->
				<?= ViewManager::renderOrderList() ?>

				<?php if (cart_old()->promotion) { ?>

					<!-- Promotion -->
					<section id="promotion" class="">
						<h3 class="title"><?= cart_old()->promotion_data['name'] ?></h3>
						<p><?= cart_old()->promotion_data['info'] ?></p>
					</section>

				<?php } else if (!cart_old()->usedProductCredit) { ?>

					<!-- Discount code -->
					<section id="code" class="">
						<h3 class="title">CODICE SCONTO</h3>
						<div id="code-anch" class="anchor"></div>
						<div id="code_cont" method="POST" action="#code-anch">

							<?php if (!cart_old()->couponId) { ?>

								<input type="text" id="code-inp" name="code" class="input wide" placeholder="Codice sconto (opzionale)" onFocus="$('.err').hide()">
								<?php if ($error == 'coupon-invalid') { ?><p class="error mar-t-1">Il codice inserito non esiste</p><?php } ?>
								<?php if ($error == 'coupon-expired') { ?><p class="error mar-t-1">Il codice inserito è scaduto</p><?php } ?>
								<?php if ($error == 'coupon-not-active') { ?><p class="error mar-t-1">Il codice inserito non è attivo</p><?php } ?>
								<?php if ($error == 'coupon-not-logged') { ?><p class="error mar-t-1">Devi essere registrato per poter usare questo codice</p><?php } ?>
								<?php if ($error == 'coupon-used') { ?><p class="error mar-t-1">Hai già usato il codice inserito</p><?php } ?>

								<button type="submit" id="add_code" name="add_code" class="btn button--full-width mar-t-1" formnovalidate>Aggiungi</button>

							<?php } else { ?>

								<p class="code_txt">
									Codice usato: <span class="code txt-c1"><?= cart_old()->coupon->code ?></span>
								</p>
								<button type="submit" name="del_code" id="code_remove" formnovalidate>Rimuovi</button>

							<?php } ?>

						</div>
					</section>

				<?php } ?>

				<?php if (is_seller() && !cart_old()->couponId && cart_old()->customer->credit) { ?>

					<!-- Used credit panel -->
					<section id="credit-section" class="">
						<h3 class="title">Credito</h3>

						<p>Per ogni vendita realizzata ottieni un <strong>10% di bonus</strong> sui guadagni, che puoi usare per pagare le spese di spedizione oppure per comprare articoli del tuo negozio.<br><br>Questo credito non può essere usato insieme ad un codice sconto ed è possibile usarlo solo se si ha abbastanza credito da coprire l'intero prezzo del prodotto.</p>

						<p class="mar-t-1 txt-med txt-1rem txt-cen">Hai usato <strong><?= formatc(cart_old()->usedCredit) ?> € / <?= formatc(cart_old()->customer->credit) ?> €</strong></p>

						<div class="total-credit">
							<div class="used-credit" style="width: <?= round(cart_old()->usedCredit / cart_old()->customer->credit * 100) ?>%"></div>
						</div>


					</section>

				<?php } ?>

				<!-- Recap -->
				<?= CartRecap::render() ?>

			</div>
		</div>
	</div>
</form>

{% end main %}