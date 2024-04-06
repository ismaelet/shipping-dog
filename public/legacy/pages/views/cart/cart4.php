<?php

use Ecommerce\ShippingMethod;
use ViewManager\Ecommerce\CartFlowBar;
use ViewManager\Ecommerce\CartRecap;

use function Ecommerce\cart;

?>

{% block main %}

<div id="slider">
	<div class="bgr"></div>
	<h1 id="slider-title">CA<span class="txt-c1">RR</span>ELLO</h1>
</div>

<div id="body">
	<div class="main-wrap">

		<?= CartFlowBar::render() ?>

		<div class="grid-flow" data-spacing="700">

			<?php if ($warning) { ?>

				<p id="news" class="<?= $warning['color'] ?>"><?= $warning['text'] ?></p>

			<?php } ?>

			<?php if (cart()->shippingMethodId == ShippingMethod::NO_DELIVERY_SHIPPING_METHOD_ID) { ?>

				<p class="center-content error-warning">Hai scelto di ritirare il capo in sede, quindi l'ordine NON verrà spedito, ma dovrai ritirarlo nella nostra sede in Piazza Scopoli, 9, 38033 Cavalese (TN)</p>

			<?php } ?>

			<div>
				<h1 class="title">PAGAMENTO</h1>

				<form id="pay_panel" class="mar-b-2" method="POST">
					<div class="data_col" style="width: 20%;"></div>
					<div class="data_col" style="width: 80%;"></div>

					<?php while ($row = $res_payments->fetch_assoc()) { ?>

						<label class="pay_row">
							<div class="pay_cbx_box">
								<input type="radio" class="inp_cbx pay_cbx" name="cbx_pay" value="<?= $row['ID'] ?>" <?php if (cart()->paymentMethodId == $row['ID']) echo 'checked'; ?>>

								<div class="icon">
									<div class="icon-dot"></div>
								</div>
							</div>

							<div class="pay_box">
								<h1 class="pay_name"><?= $row['name_it']; ?></h1>

								<?php if (file_exists(SERVER_ROOT . '/pages/assets/cart/pay_' . $row['tag'] . '.png')) { ?>
									<div class="pay_img" <?= background_image('/pages/assets/cart/pay_' . $row['tag'] . '.png'); ?>></div>
								<?php } ?>

								<p class="pay_desc"><?= $row['desc_it']; ?></p>
							</div>
						</label>
					<?php } ?>

					<button type="submit" name="submit" id="submit" class="hidden"></button>
				</form>

				<!-- Recap -->
				<?= CartRecap::render() ?>

			</div>
		</div>
	</div>
</div>

{% end main %}