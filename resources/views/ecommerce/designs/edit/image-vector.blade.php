@php
	$hasVector =
	    file_exists(base_path() . '/public/storage/designs_vector/' . $design->id . '.pdf') ||
	    file_exists(base_path() . '/public/storage/designs_vector/' . $design->id . '.ai');
@endphp

<form
	id="upload-image-vector"
	action="{{ route('designs.upload-image', [$design, 'type' => 'vector']) }}"
	method="POST"
	class="surface grid gap-4 p-6"
	enctype="multipart/form-data"
	x-data
>

	@csrf

	<div>
		<x-typography.component-heading class="flex gap-2">
			Grafica vettoriale

			@if ($hasVector)
				<span class="text-success w-[1em]">
					<x-icon-correct />
				</span>
			@endif
		</x-typography.component-heading>

		<div class="flex gap-2">
			@if ($hasVector)
				<div class="grid grid-cols-1 gap-2">
					<p class="text-success">Hai già caricato il file vettoriale</p>

					<p>
						Grazie per aver caricato il vettoriale. Se avessi bisogno di cambiarlo puoi usare il campo qui sotto.
					</p>
				</div>
			@else
				<p class="data-title inlb al-cen">(opzionale) file vettoriale in formato <span class="font-bold">PDF</span> o <span
						class="font-bold"
					>AI</span></p>

				<x-help.info direction="mobile-left">
					Oltre al file in PNG che hai già caricato, consigliamo di caricare anche il design in formato <span
						class='font-bold'
					>PDF</span> o <span class='font-bold'>AI</span> per la massima qualità di stampa possibile. Se non hai il
					design in questo formato non preoccuparti, riusciremo comunque a realizare i tuoi prodotti con stampe di alta
					qualità.
				</x-help.info>
			@endif
		</div>
	</div>

	<x-inputs.group class="grid gap-2">
		<x-inputs.input
			type="file"
			name="file_vector"
			size="small"
			accept=".pdf,.ai"
			@input="$el.closest('form').submit()"
		/>

		@error('file_vector')
			<x-inputs.alert-error>{{ $message }}</x-inputs.alert-error>
		@enderror
	</x-inputs.group>
</form>
