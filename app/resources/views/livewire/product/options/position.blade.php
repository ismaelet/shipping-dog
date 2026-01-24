<div
	id="position"
	class="field {{ count($positions) < 2 || $product->isCustom() ? 'hidden' : '' }}"
>

	<x-label class="field-label text-sm">Posizione della stampa</x-label>

	<div class="data-area">

		@foreach ($positions as $key => $position)
			<label class="data-selector data-selector--position">
				<input
					type="radio"
					class="hidden"
					name="position_id"
					value="{{ $position->id }}"
					wire:model.live="selectedPositionId"
					@checked($position->id === $selectedPositionId)
				>
				<p class="data-selector__label">{{ $position->name }}</p>
			</label>
		@endforeach

	</div>
</div>
