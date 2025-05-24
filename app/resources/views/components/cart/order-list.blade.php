@props(['order' => null])

@php
	$order = $order ?: cart();
	$orderItems = $order->order_items->load(
	    'logo',
	    'product',
	    'product_with_trashed.type',
	    'product_with_trashed',
	    'product_with_trashed.design_with_trashed',
	    'color',
	    'position',
	    'size',
	);
@endphp

<section
	id="items"
	class="cart-panel surface"
>
	<x-typography.component-heading>Articoli</x-typography.component-heading>

	<div id="items_panel">

		<?php foreach ($orderItems as $orderItem) { ?>

		<div class="row grid">
			<!--Image-->
			<?php if ($orderItem->product) { ?>
			<a
				href="<?= $orderItem->product ? $orderItem->product->url() : '' ?>"
				class="img_cont"
			>
				<img
					src="<?= $orderItem->image() ?>"
					class="img-fill"
				>
			</a>
			<?php } else { ?>
			<div>
				<img
					src="<?= public_asset(PreviewHelper::PLACEHOLDER_IMAGE) ?>"
					class="img-fill"
				>
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

			@if (!$order->status)
				<button
					type="submit"
					class="remove icon-box text-neutral-700"
					formnovalidate="formnovalidate"
					formaction="{{ route('cart.remove', $orderItem->id) }}
					"
				>
					{!! embed_icon('close') !!}
				</button>
			@endif

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

		<button
			class="use-credit-btn credit-btn btn btn--secondary button--auto-width mt-4"
			name="item_credit"
			value="<?= $orderItem->id ?>"
		>
			<?= embed_icon('dollar') ?>
			Usa il tuo credito
		</button>

		<?php } else if ($orderItem->credit) { ?>

		<button
			class="no-credit-btn credit-btn btn btn--secondary button--auto-width mt-4"
			name="item_no_credit"
			value="<?= $orderItem->id ?>"
		>
			<?= embed_icon('close') ?>
			Non usare il credito
		</button>

		<?php } ?>
		<?php } ?>

	</div>
</section>
