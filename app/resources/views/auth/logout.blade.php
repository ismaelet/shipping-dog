<x-layouts.form :title="$title">
	<x-slot:body>
		<p class="text-center">
			Desideri davvero disconnetterti?
		</p>
	</x-slot:body>

	<x-slot:footer>
		<x-button type="submit">Esci</x-button>
		<x-button
			href="/profile"
			variant="secondary"
		>Indietro</x-button>
	</x-slot:footer>
</x-layouts.form>
