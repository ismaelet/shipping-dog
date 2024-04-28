<?php

namespace Legacy\Classes\Ecommerce;

use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Product;
use Legacy\View\Components\Interactions\LikeButton;
use Illuminate\Support\Facades\Auth;

class ViewManager {

	/*
	* Render methods
	*/
	// product card
	public static function renderProductCard(Product $product) {
		ob_start();
?>

		<div class="item-box">
			<div class="image">
				<a href="<?= $product->url() ?>" class="block">
					<img src="<?= $product->image() ?>" alt="" loading="lazy">
					<p class="price blu"><?= $product->customerPriceString() ?></p>
				</a>

				<?= LikeButton::render(['design_id' => $product->design_id]) ?>

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
		$cart = $cart ?: cart_old();
		$cart = Cart::find($cart->id);

		ob_start();
	?>

		<section id="items" class="section">
			<h2 class="title">ARTICOLI</h2>

			<div id="items_panel">

				<?php foreach ($cart->orders as $order) { ?>

					<div class="row grid">
						<!--Image-->
						<?php if ($order->product) { ?>
							<a href="<?= $order->product ? $order->product->url() : '' ?>" class="img_cont">
								<img src="<?= $order->image() ?>" class="img-fill">
							</a>
						<?php } else { ?>
							<div>
								<img src="<?= public_asset(PreviewHelper::PLACEHOLDER_IMAGE) ?>" class="img-fill">
							</div>
						<?php } ?>

						<!--Data-->
						<div class="art_data">
							<a href="<?= $order->product ? $order->product->url() : null ?>">
								<h3 class="art_name"><?= $order->name() ?></h3>
							</a>

							<p class="txt-bold">
								<?= $order->discount() ? '<span class="price-old">' . $order->grossPriceString() . '</span>' : $order->priceString() ?>
							</p>

							<?php if ($order->discount()) { ?>

								<p class="price-promo txt-bold"><?= $order->priceString() ?></p>

							<?php } ?>

							<p class="order-list__description">
								<?= $order->condensedDescription() ?>
							</p>
						</div>

						<?php if (!$cart->status) { ?>

							<button type="submit" name="remove" class="remove icon-box" value="<?= $order->id ?>" formnovalidate="formnovalidate">
								<?= embed_icon('close') ?>
							</button>

						<?php } ?>

					</div>

					<?php if (!$cart->status && !$cart->coupon && !$order->credit && $order->seller_id == Auth::id() && $order->seller->credit() >= $order->price()) { ?>

						<button class="use-credit-btn credit-btn btn btn--secondary button--auto-width mar-t-1" name="item_credit" value="<?= $order->id ?>">
							<?= embed_icon('dollar') ?>
							Usa il tuo credito
						</button>

					<?php } else if (!$cart->status && $order->credit) { ?>

						<button class="no-credit-btn credit-btn btn btn--secondary button--auto-width mar-t-1" name="item_no_credit" value="<?= $order->id ?>">
							<?= embed_icon('close') ?>
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
