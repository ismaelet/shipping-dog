@php
	$hasDarkVariant = file_exists(base_path() . '/public/storage/designs/' . $design->id . '_dark.png');
@endphp

<form
	id="upload-image-dark"
	method="POST"
	class="surface grid gap-4 p-6"
	action="{{ route('designs.upload-image', [$design, 'type' => 'dark']) }}"
	enctype="multipart/form-data"
>
	@csrf

	<div>
		<x-typography.component-heading>Grafica scura</x-typography.component-heading>

		<div class="flex gap-2">
			<p>(opzionale) grafica utilizzata su capi scuri</p>
			<x-help.info direction="mobile-left">
				Se la tua grafica non è molto visibile sui capi neri, ma ne hai una versione alternativa più chiara, che
				va bene per i prodotti scuri, puoi caricare qui questa versione, così da non doverle inserire come due grafiche
				separate.
			</x-help.info>
		</div>
	</div>

	@if ($hasDarkVariant)
		{{-- If it has a dark image --}}
		<div class="bg-neutral-dark-200 grid aspect-square grid-rows-1 border">
			<x-inputs.image-replacer
				:image="public_asset('designs/' . $design->id . '_dark.png')"
				name="file_dark"
				auto-submit="true"
			/>
		</div>

		<div
			class="lg:hidden"
			x-data
		>
			<x-button
				variant="secondary"
				class="w-full"
				size="small"
				@click="$el.closest('form').querySelector('input').click()"
			>
				{!! embed_icon('upload') !!}
				Ricarica grafica
			</x-button>
		</div>
	@else
		<div
			class="grid"
			x-data
		>
			<x-input
				type="file"
				name="file_dark"
				@input="$el.closest('form').submit()"
			/>
		</div>
	@endif

	@error('file_dark')
		<x-alerts.error>{{ $message }}</x-alerts.error>
	@enderror

</form>
