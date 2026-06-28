<div class="price-block">

	@if ($product->userIsOwner())

		{{-- Price when there's a discount --}}
		<p class="price price-old font-heading">{{ $product->fullPriceString() }}</p>
		<p class="price price-promo font-heading">{{ $product->sellerPriceString() }}</p>

		<div class="flex items-center gap-2">
			<p class="price-txt">Prezzo scontato{{ $product->userIsOwner() ? ' solo per te' : '' }}</p>

			@if ($product->userIsOwner())
				<x-help.info>Questo prezzo speciale è visibile a te, perché sei il proprietario di questo articolo. I clienti
					vedranno solo il prezzo normale di <span class=''>{{ $product->fullPriceString() }}</span>
				</x-help.info>
			@endif
		</div>
	@else
		{{-- Normal price --}}
		<p class="price price-def font-heading js-price font-semibold text-neutral-700">
			{{ $product->customerPriceString() }}</p>

	@endif

</div>
