<?php

namespace Ecommerce;

use App\Models\Ecommerce\Product;
use App\View\Components\Interactions\LikeButton;

class ViewManager {

	/*
	* Render methods
	*/
	// product card
	public static function renderProductCard(object $product) {
		$product = Product::find($product->id);

		ob_start();
?>

		<div class="item-box">
			<div class="image">
				<a href="<?= $product->url() ?>" class="block">
					<img src="<?= $product->image() ?>" alt="" loading="lazy">
					<p class="price blu"><?= $product->customerPriceString() ?></p>
				</a>

				<?= LikeButton::render(['designId' => $product->designId]) ?>

			</div>

			<p class="type txt-upp"><?= $product->type->name ?></p>
			<p class="name"><?= $product->name ?></p>
			<p class="seller">by <a class="txt-c1-h" href="<?= $product->seller->url() ?>"><?= $product->seller->name ?></a></p>
		</div>

	<?php

		return ob_get_clean();
	}

	// order list
	public static function renderOrderList(object $cart = null) {
		$cart = $cart ?: cart();

		ob_start();
	?>

		<section id="items" class="section">
			<h2 class="title">ARTICOLI</h2>

			<div id="items_panel">

				<?php foreach ($cart->orders as $order) { ?>

					<div class="row grid">
						<!--Image-->
						<a href="<?= $order->product->clientUrl ?>" class="img_cont">
							<img src="<?= $order->product->clientImage ?>" class="img-fill">
						</a>

						<!--Data-->
						<div class="art_data">
							<a href="<?= $order->product->clientUrl ?>">
								<h3 class="art_name"><?= $order->name ?></h3>
							</a>

							<p class="txt-bold">
								<?= $order->discount ? '<span class="price-old">' . $order->fullPriceString . '</span>' : $order->priceString ?>
							</p>

							<?php if ($order->discount) { ?>

								<p class="price-promo txt-bold"><?= $order->priceString ?></p>

							<?php } ?>

							<p class="order-list__description">
								<?= $order->fullDescription ?>
							</p>
						</div>

						<?php if (!$cart->statusId) { ?>

							<button type="submit" name="remove" class="remove icon-box" value="<?= $order->id ?>" formnovalidate="formnovalidate">
								<?= icon_embed('close') ?>
							</button>

						<?php } ?>

					</div>

					<?php if (!$cart->statusId && !$cart->code && !$order->credit && $order->product->sellerId == $cart->customerId && $cart->usableCredit >= $order->price) { ?>

						<button class="use-credit-btn credit-btn button button--secondary button--auto-width mar-t-1" name="item_credit" value="<?= $order->id ?>">
							<?= icon_embed('dollar') ?>
							Usa il tuo credito
						</button>

					<?php } else if (!$cart->statusId && $order->credit) { ?>

						<button class="no-credit-btn credit-btn button button--secondary button--auto-width mar-t-1" name="item_no_credit" value="<?= $order->id ?>">
							<?= icon_embed('close') ?>
							Non usare il credito
						</button>

					<?php } ?>
				<?php } ?>

			</div>
		</section>

<?php

		return ob_get_clean();
	}
}
