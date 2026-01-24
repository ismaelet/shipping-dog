@props(['product'])

@php
	$sizes = $product->type->sizes;
	$showTable = $product->type->measures?->count() && $product->type->category == 'c' && !$product->isCustom();

	$label = $product->type->category == 'c' ? 'Taglia' : 'Dimensione';
@endphp

<section
	id="size"
	class="field"
>
	<x-label class="field-label text-sm">

		{{ $label }}

		@if ($showTable)
			<span
				class="lbox-open clickable info txt-c1-h"
				data-tar="#size-lbox"
			>info taglie</span>
		@endif

	</x-label>

	<div class="grid">

		@foreach ($sizes as $size)
			<label class="size_box">
				<input
					type="radio"
					class="cbx"
					name="size_id"
					value="{{ $size->id }}"
					@checked($loop->first)
				>

				<div class="size_lbl">
					<h1 class="size_txt uppercase">{{ $size->name }}</h1>
				</div>
			</label>
		@endforeach

	</div>

	{{-- Size table --}}
	@if ($showTable)
		<x-product.measure-table
			:$product
			:$showTable
		/>
	@endif

</section>
