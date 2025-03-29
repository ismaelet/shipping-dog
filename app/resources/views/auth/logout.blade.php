<x-layouts.form :title="$title">
	<x-slot:body>
		<p class="text-center">
			Desideri davvero disconnetterti?
		</p>
	</x-slot:body>

	<x-slot:footer>
		<x-inputs.button type="submit">Esci</x-inputs.button>
		<x-inputs.button
			href="/profile"
			variant="secondary"
		>Indietro</x-inputs.button>
	</x-slot:footer>
</x-layouts.form>
