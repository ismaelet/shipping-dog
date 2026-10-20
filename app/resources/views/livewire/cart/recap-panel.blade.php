@pushOnce('styles')
	<style>
		.chck_item:not(:last-child) {
			border-bottom: var(--spacing-border) solid var(--color-surface-500);
		}
	</style>
@endpushOnce

<div class="">
	<div class="grid grid-cols-1 gap-4">
		<div class="chck_item flex justify-between">
			<p class="">Articoli</p>
			<p class="js-cart-products-price text-neutral-heading font-semibold">{{ price($order->fullOrderItemsPrice()) }}</p>
		</div>

		<div class="chck_item flex justify-between">
			<p>Spedizione</p>
			<p
				class="js-cart-shipping-price-string js-recap-shipping-price text-neutral-heading font-semibold"
				data-shipping-method-id="{{ $order->shippingMethodId }}"
			>{{ price($order->shippingFee()) }}</p>
		</div>

		@if ($order->discount())
			<div class="chck_item flex justify-between">
				<p>Sconto</p>
				<p class="text-neutral-heading font-semibold">- {{ price($order->discount()) }}</p>
			</div>
		@endif

		<div
			id="total"
			class="chck_item flex justify-between"
		>
			<p class="chck_name">Totale</p>
			<p class="chck_pric js-cart-price-string text-neutral-heading font-semibold">{{ price($order->price) }}</p>
		</div>

		@if ($order->usedCredit())
			<div class="chck_item flex justify-between">
				<p class="text-secondary">Credito usato</p>
				<p class="text-secondary font-semibold">{{ price($order->usedCredit()) }}</p>
			</div>
		@endif

		@if ($order->statusTag() === 'open')
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
		@endif
	</div>
</div>
