@props(['order' => null])

@php

	$order = $order ?: cart();

@endphp

<div
	id=""
	class="cart-panel surface"
>
	<x-typography.component-heading>Riepilogo</x-typography.component-heading>
	<div class="grid grid-cols-1 gap-4">
		<div class="chck_item flex justify-between">
			<p class="">Articoli</p>
			<p class="js-cart-products-price font-semibold text-neutral-700"><?= $order->fullOrderItemsPriceString() ?></p>
		</div>

		<div class="chck_item flex justify-between">
			<p>Spedizione</p>
			<p
				class="js-cart-shipping-price-string js-recap-shipping-price font-semibold text-neutral-700"
				data-shipping-method-id="<?= $order->shippingMethodId ?>"
			><?= $order->shippingFeeString() ?></p>
		</div>

		<?php if ($order->discount()) { ?>

		<div class="chck_item flex justify-between">
			<p>Sconto</p>
			<p class="font-semibold text-neutral-700">- <?= $order->discountString() ?></p>
		</div>

		<?php } ?>

		<?php if ($order->usedCredit()) { ?>

		<div class="chck_item flex justify-between">
			<p>Credito</p>
			<p class="font-semibold text-neutral-700">-<?= $order->usedCreditString ?></p>
		</div>

		<?php } ?>

		<div
			id="total"
			class="chck_item flex justify-between"
		>
			<p class="chck_name">Totale</p>
			<p class="chck_pric js-cart-price-string font-semibold text-neutral-700"><?= $order->priceString() ?></p>
		</div>

		<?php if (in_array($order->statusTag(), ['open', 'received'])) { ?>

		<div
			id="chck_btn_box"
			class="gap1 grid"
		>
			<x-button
				type="submit"
				id="checkout"
				class="btn button--full-width"
				onclick="$('#submit').click();"
			>Prosegui</x-button>
			<x-button
				class="btn btn--secondary button--full-width"
				href="{{ route('shop') }}"
				variant="secondary"
			>Continua con gli acquisti</x->
		</div>


		<?php } ?>

	</div>
</div>
