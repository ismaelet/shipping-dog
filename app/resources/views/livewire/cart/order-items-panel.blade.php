<section
	id="items"
	class="cart-panel surface"
>
	<x-typography.component-heading>Articoli</x-typography.component-heading>

	<div id="items_panel">

		@foreach ($order->order_items as $orderItem)
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
						<h4 class=""><?= $orderItem->name() ?></h3>
					</a>

					<p class="text-strong">
						@if ($orderItem->discount())
							<span class="price-old">{{ $orderItem->grossPriceString() }}</span>
						@else
							{{ $orderItem->priceString() }}
						@endif

						@if ($orderItem->discount())
							<span class="text-error-500 font-bold">{{ $orderItem->priceString() }}</span>
						@endif
					</p>

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
		@endforeach

	</div>
</section>
