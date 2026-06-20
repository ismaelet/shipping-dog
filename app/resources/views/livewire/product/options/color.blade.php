@props(['product'])

<section
	id="color"
	class="field {{ $product->colors->count() < 2 ? 'hide' : '' }}"
>
	<x-label class="field-label text-sm">Colore</x-label>

	<div class="grid">

		@foreach ($product->colors as $color)
			<label
				class="color-box"
				title="{{ $color->name }}"
			>
				<input
					type="radio"
					class="cbx"
					name="color_id"
					value="{{ $color->id }}"
					@checked($product->color?->id ? $color->id == $product->color->id : $loop->first)
					wire:model.live="selectedColorId"
				>

				<div
					class="color-lbl"
					style="background-color: {{ $color->primary_color_hex }}"
				>

					@if ($color->secondary_color_hex)
						<span
							class="secondary-color"
							style="background-color: {{ $color->secondary_color_hex }}"
						></span>
					@endif

				</div>
			</label>
		@endforeach

	</div>
</section>
