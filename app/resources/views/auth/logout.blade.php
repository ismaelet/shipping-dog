<x-layouts.form :$title>
	<x-slot:body>
		<p class="text-center">
			Desideri davvero disconnetterti?
		</p>
	</x-slot:body>

	<x-slot:footer>
		<x-button type="submit">
			Esci
			<x-icon-logout />
		</x-button>

		<x-button
			:href="route('user-dashboard')"
			variant="secondary"
		>Indietro</x-button>
	</x-slot:footer>
</x-layouts.form>
