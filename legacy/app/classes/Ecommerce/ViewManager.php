<?php

namespace Legacy\Classes\Ecommerce;

use App\Helpers\PreviewHelper;
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

				<?= LikeButton::render(['design' => $product->design]) ?>

			</div>

			<div class="item-box-body">
				<p class="type uppercase"><?= $product->type->name ?></p>
				<p class="name"><?= $product->name ?></p>
				<p class="seller">by <a class="txt-c1-h" href="<?= $product->seller->url() ?>"><?= $product->seller->name ?></a></p>
			</div>
		</div>

	<?php

		return ob_get_clean();
	}

	// order list
	public static function renderOrderList(object $order = null) {
		$order = $order ?: cart();

		ob_start();
	?>

		<section id="items" class="section">
			<h2 class="title">ARTICOLI</h2>

			<div id="items_panel">

				<?php foreach ($order->order_items->load('logo') as $orderItem) { ?>

					<div class="row grid">
						<!--Image-->
						<?php if ($orderItem->product) { ?>
							<a href="<?= $orderItem->product ? $orderItem->product->url() : '' ?>" class="img_cont">
								<img src="<?= $orderItem->image() ?>" class="img-fill">
							</a>
						<?php } else { ?>
							<div>
								<img src="<?= public_asset(PreviewHelper::PLACEHOLDER_IMAGE) ?>" class="img-fill">
							</div>
						<?php } ?>

						<!--Data-->
						<div class="art_data">
							<a href="<?= $orderItem->product ? $orderItem->product->url() : null ?>">
								<h3 class="art_name"><?= $orderItem->name() ?></h3>
							</a>

							<p class="font-semibold">
								<?= $orderItem->discount() ? '<span class="price-old">' . $orderItem->grossPriceString() . '</span>' : $orderItem->priceString() ?>
							</p>

							<?php if ($orderItem->discount()) { ?>

								<p class="price-promo font-semibold"><?= $orderItem->priceString() ?></p>

							<?php } ?>

							<p class="order-list__description">
								<?= $orderItem->multilineDescription() ?>
							</p>
						</div>

						<?php if (!$order->status) { ?>

							<button type="submit" name="remove" class="remove icon-box" value="<?= $orderItem->id ?>" formnovalidate="formnovalidate">
								<?= embed_icon('close') ?>
							</button>

						<?php } ?>

					</div>

					<?php

					$userSeller = Auth::user()?->seller;

					if (
						$userSeller &&
						$userSeller->id == $orderItem->seller_id &&
						$userSeller->credit >= $orderItem->price() &&
						!$order->coupon &&
						!$orderItem->credit
					) { ?>

						<button class="use-credit-btn credit-btn btn btn--secondary button--auto-width mar-t-1" name="item_credit" value="<?= $orderItem->id ?>">
							<?= embed_icon('dollar') ?>
							Usa il tuo credito
						</button>

					<?php } else if ($orderItem->credit) { ?>

						<button class="no-credit-btn credit-btn btn btn--secondary button--auto-width mar-t-1" name="item_no_credit" value="<?= $orderItem->id ?>">
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
