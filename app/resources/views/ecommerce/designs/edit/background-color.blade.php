@pushOnce('scripts')
	<script defer>
		function change_color(event) {
			const color = event.target.value;

			document.querySelector('[data-color-hex-string]').textContent = color;
			document.querySelector('[data-color-background]').style.backgroundColor = color;
		}
	</script>
@endpushOnce

<form
	id="background-color"
	action="{{ route('designs.update-background-color', $design) }}"
	class="surface grid gap-8 p-6"
	x-data
	method="POST"
>
	@csrf

	<div class="grid gap-2">
		<x-typography.component-heading>Colore di sfondo</x-typography.component-heading>
		<p>
			Questo colore verrà usato come sfondo della tua grafica in alcune sezioni del sito. Scegli un colore che faccia
			risaltare bene la tua immagine. Non inflenza in alcun modo la stampa o il colore dei capi su cui la grafica sarà
			acquistabile.
		</p>
	</div>

	@if (session('saved-background-color'))
		<x-inputs.alert-success>Modifiche salvate</x-inputs.alert-success>
	@endif

	{{-- Background color --}}
	<x-inputs.group>
		<x-inputs.label text="Scegli il colore:" />

		<div class="flex items-center gap-2">
			<x-input
				type="color"
				name="color"
				class="cursor-pointer"
				value="{{ $design->color }}"
				@input="change_color(event); $el.closest('form').querySelector('[data-button-container]').classList.remove('hidden')"
			/>

			<p class="">
				HEX: <span
					class="font-bold uppercase"
					data-color-hex-string
				>{{ $design->color }}</span>
			</p>
		</div>
	</x-inputs.group>

	<div
		class="hidden"
		data-button-container
	>
		<x-button
			type="submit"
			class="w-full"
		>Salva</x-button>
	</div>
</form>
