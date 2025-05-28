@php
	$colors = $product->type->colors->sortBy('hidden')->sortBy('preorder')->sortBy('reorder');
@endphp

@pushOnce('scripts')
	<script>
		function updateDefaultColor(activeColor) {
			const colorId = activeColor.dataset.activeColor;
			const colorPanel = activeColor.closest('[data-panel]');
			const defaultColor = colorPanel.querySelector('[data-default-color="' + colorId + '"]');
			const activeColorCount = colorPanel.querySelectorAll('[data-active-color]:checked').length;

			let active = activeColor.checked;

			if (!activeColorCount) {
				active = true;
				activeColor.checked = true;
			}

			if (active) {
				defaultColor.closest('label').classList.remove('hidden');
			} else {
				defaultColor.closest('label').classList.add('hidden');

				const wasChecked = defaultColor.checked;

				defaultColor.checked = false;

				if (wasChecked) {
					const firstActiveColor = colorPanel.querySelector('label:not(.hidden) [data-default-color]').checked =
						true;
				}
			}
		}
	</script>
@endpushOnce

<div
	class="grid gap-2"
	x-data
>
	<div class="flex items-center gap-2">
		<x-label text="Colori attivi" />

		<x-help.info direction="center">È possibile comprare la tua grafica stampata su capi di questi colori. Attiva
			quelli che ti
			piacciono e disattiva quelli che non vuoi che siano disponibili per l'acquisto.</x-help.info>
	</div>

	<div class="flex flex-wrap gap-2">
		@foreach ($colors as $color)
			<label
				class="has-[:checked]:border-primary border-xl aspect-square w-8 shrink-0 cursor-pointer rounded-full transition-colors"
				style="background-color: {{ $color->primary_color_hex }};"
				title="{{ $color->name }}"
			>
				<input
					type="checkbox"
					class="hidden"
					name="colors[]"
					value="{{ $color->tag }}"
					data-active-color="{{ $color->id }}"
					@checked($product->colors()->contains('id', $color->id))
					@input="updateDefaultColor($event.target); showSaveButton($event.target)"
				>
			</label>
		@endforeach
	</div>
</div>
