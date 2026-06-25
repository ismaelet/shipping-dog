@props(['colors', 'productId'])

<div class="grid gap-2">
	<div class="flex items-center gap-2">
		<x-label text="Colori attivi" />

		<x-help.info direction="center">
			È possibile comprare la tua grafica stampata su capi di questi colori.
			Attiva quelli che ti piacciono e disattiva quelli che non vuoi rendere
			disponibili per l'acquisto.
		</x-help.info>
	</div>

	<div class="flex flex-wrap gap-2">
		@foreach ($colors as $color)
			<label
				class="has-checked:border-primary aspect-square w-8 shrink-0 cursor-pointer rounded-full border-2 transition-colors"
				style="background-color: {{ $color->primary_color_hex }};"
				title="{{ $color->name }}"
				wire:key="active-color-{{ $productId }}-{{ $color->id }}"
			>
				<input
					type="checkbox"
					class="hidden"
					value="{{ $color->id }}"
					wire:model.live="activeColorIds"
				>
			</label>
		@endforeach
	</div>

	@error('activeColorIds')
		<p class="text-sm text-red-600">
			{{ $message }}
		</p>
	@enderror

	@error('activeColorIds.*')
		<p class="text-sm text-red-600">
			{{ $message }}
		</p>
	@enderror
</div>
