@php
	$colors = $product->type->colors->sortBy('hidden')->sortBy('preorder')->sortBy('reorder');
@endphp

<form
	class="grid gap-2"
	x-data
>
	<div class="flex items-center gap-2">
		<x-inputs.label text="Colore predefinito" />
		<x-help.info direction="center">Qui puoi selezionare il colore del capo che sarà mostrato come prima scelta in
			tutte le
			anteprime.</x-help.info>
	</div>

	<div class="flex flex-wrap gap-2">
		@foreach ($colors as $color)
			<label
				@class([
					'color-checkbox has-[:checked]:border-primary border-xl aspect-square w-8 shrink-0 cursor-pointer rounded-full transition-colors',
					'hidden' => !$product->colors()->contains('id', $color->id),
				])
				style="background-color: {{ $color->primary_color_hex }};"
				title="{{ $color->name }}"
			>
				<input
					type="radio"
					class="hidden"
					name="product_color_id"
					value="{{ $color->id }}"
					data-default-color="{{ $color->id }}"
					data-default-color-tag="{{ $color->tag }}"
					@checked($color->id == $product->color->id)
					@input="showSaveButton($event.target)"
				>
			</label>
		@endforeach
	</div>
</form>
