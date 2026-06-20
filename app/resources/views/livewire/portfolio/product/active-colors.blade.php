<div class="form-field">
	<div>
		<x-label>Colori attivi:</x-label>
		<p>Tutte le varianti di colore possibili per il prodotto</p>
	</div>

	<div
		class="colors mt-4"
		id="active-colors"
	>

		@foreach ($availableColors as $color)
			<div class="color js-color">
				<label class="">
					<input
						id="active-color-{{ $color->id }}"
						type="checkbox"
						class="color__checkbox js-active-color"
						name="colors[]"
						value="{{ $color->id }}"
						@checked(in_array($color->id, $activeColorIds))
						wire:change="toggleColor({{ $color->id }})"
					>

					<div
						class="color__icon"
						style="--_color-1: {{ $color->primary_color_hex }}; --_color-2: {{ $color->secondary_color_hex ?: $color->primary_color_hex }}"
					></div>
				</label>

				<div class="color__content">
					<label for="active-color-{{ $color->id }}">
						<p class="color__label js-active-color-label">
							{{ $color->name }}
						</p>
					</label>
				</div>
			</div>
		@endforeach

	</div>
</div>
