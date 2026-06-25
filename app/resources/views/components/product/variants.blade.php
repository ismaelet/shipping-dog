@use('Legacy\Models\Ecommerce\Product')
@use('Legacy\Models\Ecommerce\Product\ProductType')

@php

	$mainType = $product->type->shownType();
	$mainType->variants->prepend($mainType);

@endphp

<section
	id="gender"
	class="field relative"
>

	<div class="">
		<x-label class="field-label text-sm">Tipologia</x-label>
	</div>

	<div class="flex flex-wrap gap-2">

		@foreach ($mainType->variants as $variant)
			@php
				$relatedProduct = $variant->products->where('design_id', $product->design_id)->first();
			@endphp

			@if ($relatedProduct)
				<div class="">
					<input
						type="radio"
						name="variant_id"
						class="hidden"
						@checked($product->type->id == $variant->id)
					>

					<a
						href="{{ $relatedProduct->url() }}"
						class="gend_lbl"
					>
						<p class="gend_txt">{{ $variant->name }}</p>
					</a>
				</div>
			@endif
		@endforeach

	</div>
</section>
