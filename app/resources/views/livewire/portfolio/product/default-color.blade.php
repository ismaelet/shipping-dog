<div class="form-field">
	<div>
		<x-label>Colore predefinito:</x-label>
		<p>Il colore che viene mostrato di base nell'anteprima del prodotto</p>
	</div>

	@if ($colors)
		<div class="colors mt-4">

			@foreach ($colors as $color)
				<label
					class="color js-color"
					wire:key="default-color-{{ $color->id }}"
				>
					<div class="">
						<input
							type="radio"
							class="color__checkbox js-default-color"
							name="default-color-id"
							value="{{ $color->id }}"
							required
							wire:model.live="defaultColorId"
						>
						<div
							class="color__icon"
							style="--_color-1: {{ $color->primary_color_hex }}; --_color-2: {{ $color->secondary_color_hex ?: $color->primary_color_hex }}"
						></div>
					</div>

					<p class="color__label js-default-color-label">
						{{ $color->name }}
					</p>
				</label>
			@endforeach

		</div>
	@else
		<p class="text-sm text-neutral-500">
			Non ci sono colori attivi per questo prodotto. Seleziona almeno un colore per poter scegliere un colore predefinito.
		</p>

	@endif
</div>
