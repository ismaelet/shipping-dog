@php
	$description = $product->type->info?->formattedDescription();
	$details = $product->type->info?->formattedDetails();
@endphp

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/product-info.css') }}"
	>
@endpushOnce

@if ($description || $details)
	<div class="mt-8">
		@if ($description)
			<h3 class="desc_stitle">Descrizione</h3>
			<p class="desc_txt">{!! $description !!}</p>
		@endif

		@if ($details)
			<h3 class="desc_stitle">Dettagli prodotto</h3>
			<p class="desc_txt">{!! $details !!}</p>
		@endif
	</div>
@endif
