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
		.order-list {
			grid-template-columns: max-content max-content minmax(0, 1fr) max-content;
		}

		.grid-cols-subgrid {
			grid-template-columns: subgrid;
		}
	</style>
@endpushOnce

<div class="">

	<a
		href="{{ $getRecord()->recapUrlWithToken() }}"
		target="_blank"
		class="underline"
	>
		Pagina di riepilogo ordine pubblica
	</a>

	<div class="order-list grid gap-x-4">

		{{-- Table header --}}
		{{-- <div class="order-row grid-cols-subgrid col-span-full grid">
		<p class="font-medium text-neutral-300">ID</p>
		<p class="font-medium text-neutral-300">Prodotto</p>
		<p class="font-medium text-neutral-300">Dettagli</p>
		<p class="font-medium text-neutral-300">Prezzo</p>
	</div> --}}

		<div
			class="order-row col-span-full grid grid-cols-subgrid items-center border-b border-gray-200 py-4 dark:border-white/10"
		>
			<p class="">Articoli</p>
			<span></span>
			<span></span>
			<p class="text-right">{{ price($getRecord()->orderItemsPrice()) }}</p>
		</div>

		@if ($getRecord()->coupon)
			<div class="order-row col-span-full grid grid-cols-subgrid">
				<p class="">Codice</p>
				<span></span>
				<p class="">{{ $getRecord()->code }}</p>
			</div>
		@endif

		@if ($getRecord()->discount())
			<div class="order-row col-span-full grid grid-cols-subgrid border-b py-4 dark:border-white/10">
				<p class="">Sconto</p>
				<span></span>
				<span></span>
				<p class="text-right">-{{ $getRecord()->discountString() }}</p>
			</div>
		@endif

		<div class="order-row col-span-full grid grid-cols-subgrid border-b py-4 dark:border-white/10">
			<p class="">Spedizione</p>
			<span></span>
			<span></span>
			<p class="text-right">{{ $getRecord()->shippingFeeString() }}</p>
		</div>

		<div class="order-row col-span-full grid grid-cols-subgrid pt-4 text-lg text-neutral-700">
			<p class="">Totale</p>
			<span></span>
			<span></span>
			<p class="text-right">{{ $getRecord()->priceString() }}</p>
		</div>
	</div>
</div>
