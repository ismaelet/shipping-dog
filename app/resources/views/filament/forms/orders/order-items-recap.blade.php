@php

	$orderItems = $getRecord()->order_items->load(
	    'product',
	    'product.type',
	    'product_with_trashed',
	    'product_with_trashed.type',
	    'product_with_trashed.design_with_trashed',
	    'type',
	    'color',
	    'position',
	    'size',
	    'logo',
	);

@endphp

@pushOnce('styles')
	<style>
		.grid-cols-subgrid {
			grid-template-columns: subgrid;
		}

		.grid {
			display: grid;
		}

		.grid-cols-2 {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}

		.grid-cols-subgrid {
			grid-template-columns: subgrid;
		}

		.gap-x-4 {
			column-gap: 1rem;
		}

		.col-span-full {
			grid-column: 1 / -1;
		}

		.block {
			display: block;
		}

		.inline {
			display: inline;
		}

		.w-4 {
			width: 1rem;
		}

		.w-full {
			width: 100%;
		}

		.h-4 {
			height: 1rem;
		}

		.pt-4 {
			padding-top: 1rem;
		}

		.pb-4 {
			padding-bottom: 1rem;
		}

		.py-4 {
			padding-top: 1rem;
			padding-bottom: 1rem;
		}

		.mb-4 {
			margin-bottom: 1rem;
		}

		.mb-8 {
			margin-bottom: 2rem;
		}

		.border-b {
			border-bottom-width: 1px;
		}

		.border-gray-200 {
			border-color: #e5e7eb;
		}

		.dark .dark\:border-white\/10 {
			border-color: rgba(255, 255, 255, 0.1);
		}

		.text-primary-400 {
			color: hsl(35deg 100% 50%);
		}

		.hover\:text-primary-500:hover {
			color: hsl(27deg 100% 45%);
		}

		.text-lg {
			font-size: 1.125rem;
		}

		.underline {
			text-decoration: underline;
		}

		.fill-current {
			fill: currentColor;
		}

		.transition-colors {
			transition-property: color;
			transition-duration: 150ms;
			transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
		}

		.order-row {
			padding-block: .5rem;
		}

		.order-row:first-child {
			padding-top: 0;
		}

		.order-row:last-child {
			padding-bottom: 0;
		}

		.order-row>p:last-child {
			text-align: right;
		}
	</style>
@endpushOnce

<div class="price-recap">

	<a
		href="{{ $getRecord()->recapUrlWithToken() }}"
		target="_blank"
		class="text-primary-400 hover:text-primary-500 mb-8 block underline transition-colors"
	>
		Pagina di riepilogo che vede il cliente
		<x-icon-external_link class="inline h-4 w-4 fill-current" />
	</a>

	<div class="grid w-full grid-cols-2 gap-x-4">
		<div class="order-row col-span-full grid grid-cols-subgrid border-b border-gray-200 dark:border-white/10">
			<p class="">Articoli</p>
			<p class="">{{ price($getRecord()->orderItemsPrice()) }}</p>
		</div>

		@if ($getRecord()->coupon)
			<div class="order-row col-span-full grid grid-cols-subgrid">
				<p class="">Codice</p>
				<p class="">{{ $getRecord()->code }}</p>
			</div>
		@endif

		@if ($getRecord()->discount())
			<div class="order-row col-span-full grid grid-cols-subgrid border-b dark:border-white/10">
				<p class="">Sconto</p>
				<p class="">-{{ $getRecord()->discountString() }}</p>
			</div>
		@endif

		<div class="order-row col-span-full grid grid-cols-subgrid border-b dark:border-white/10">
			<p class="">Spedizione</p>
			<p class="">{{ $getRecord()->shippingFeeString() }}</p>
		</div>

		<div class="order-row text-heading-400 col-span-full grid grid-cols-subgrid text-lg">
			<p class="">Totale</p>
			<p class="">{{ $getRecord()->priceString() }}</p>
		</div>
	</div>
</div>
