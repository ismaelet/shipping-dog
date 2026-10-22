@php

	$genders = [];
	$genders[] = $product->type->gender;
	$sibling = $product->type->sibling;

	if ($sibling) {
	    $genders[] = $sibling->gender;
	}

@endphp

<section
	id="gender"
	class="field relative"
>

	<div class="flex items-center gap-2">
		<x-label class="field-label text-sm">Modello</x-label>
	</div>

	<div class="grid columns-[10rem] gap-2">

		@foreach ($genders as $key => $gender)
			@php
				$relatedProduct = null;

				if ($key && $product->design_id) {
				    $relatedProduct = App\Models\Ecommerce\Product::where('design_id', $product->design_id)
				        ->where('type_id', $sibling->id)
				        ->first();
				}
			@endphp

			@if (!$key || $relatedProduct)
				<a
					class="data-selector__label"
					@if ($relatedProduct) href="{{ $relatedProduct->url() }}" @endif
				>
					<input
						type="radio"
						class="cbx"
						name="gender_id"
						value="{{ $gender->id }}"
						{{ !$relatedProduct ? 'checked' : '' }}
					>

					{{ $gender->name }}
				</a>
			@endif
		@endforeach

	</div>

</section>
