@php
	$relatedProducts = $product->relatedProducts();
@endphp

<!-- More items -->
<section
	id="more-sec"
	class="py-14 max-sm:py-10"
>
	@if ($relatedProducts->isNotEmpty())
		<div class="mb-6">
			<p class="text-[11px] font-bold tracking-wide text-primary-200 uppercase">Il catalogo del creator</p>
			<h2 class="font-heading text-3xl font-semibold text-heading-400">Altro di questo negozio</h2>

		</div>

		<div
			id="more-gall"
			class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 lg:grid-cols-4"
		>

			@foreach ($relatedProducts as $relatedProduct)
				<x-product-card
					:product="$relatedProduct"
					:display-badge="false"
					:light="false"
				/>
			@endforeach
		</div>
	@endif

</section>
