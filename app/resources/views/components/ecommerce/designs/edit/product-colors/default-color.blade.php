@props(['colors', 'productId', 'activeColorIds'])

<div class="grid gap-2">
	<div class="flex items-center gap-2">
		<x-label text="Colore predefinito" />

		<x-help.info direction="center">
			Qui puoi selezionare il colore del capo che sarà mostrato
			come prima scelta in tutte le anteprime.
		</x-help.info>
	</div>

	<div class="flex flex-wrap gap-2">
		@foreach ($colors as $color)
			<label
				@class([
					'color-checkbox has-[:checked]:border-primary aspect-square w-8 shrink-0 cursor-pointer rounded-full border-2 transition-colors',
					'hidden' => !in_array($color->id, $activeColorIds, true),
				])
				style="background-color: {{ $color->primary_color_hex }};"
				title="{{ $color->name }}"
				wire:key="default-color-{{ $productId }}-{{ $color->id }}"
			>
				<input
					type="radio"
					class="hidden"
					name="default-color-{{ $productId }}"
					value="{{ $color->id }}"
					wire:model.live="defaultColorId"
				>
			</label>
		@endforeach
	</div>

	@error('defaultColorId')
		<p class="text-sm text-red-600">
			{{ $message }}
		</p>
	@enderror
</div>
