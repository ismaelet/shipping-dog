@use('App\Models\Ecommerce\Product\ProductColor')

@php
	$colors = ProductColor::where('hidden', 0)
	    ->orderBy('hidden', 'asc')
	    ->orderBy('reorder', 'asc')
	    ->orderBy('id', 'asc')
	    ->get();
@endphp

<form
	id="default-color"
	action="{{ route('designs.update-default-color', $design) }}"
	class="surface grid gap-8 p-6"
	x-data
	method="POST"
>
	@csrf

	<div class="grid gap-2">
		<x-typography.component-heading>Colori</x-typography.component-heading>

		<p>
			Qui puoi selezionare il colore del capo che sarà mostrato come prima scelta in tutte le anteprime. Non influenza i
			capi che non hanno questo colore disponibile.
		</p>
	</div>

	@if (session('saved-default-color'))
		<x-alerts.success>Modifiche salvate</x-alerts.success>
	@endif

	{{-- Default color --}}
	<x-inputs.group>
		<x-label text="Scegli il colore:" />

		<div class="flex flex-wrap gap-2">
			@foreach ($colors as $color)
				<label
					class="has-[:checked]:border-primary aspect-square w-8 shrink-0 cursor-pointer rounded-full border-2 transition-colors"
					style="background-color: {{ $color->primary_color_hex }};"
					title="{{ $color->name }}"
				>
					<input
						type="radio"
						class="hidden"
						name="default_color_id"
						value="{{ $color->id }}"
						@checked($color->id == $design->default_color_id)
						@input="$el.closest('form').querySelector('[data-button-container]').classList.remove('hidden')"
					>
				</label>
			@endforeach
		</div>

	</x-inputs.group>

	<div
		class="hidden"
		data-button-container
	>
		<x-button
			type="submit"
			class="w-full"
		>Applica a <span class="font-bold">TUTTI</span> i capi</x-button>
	</div>
</form>
