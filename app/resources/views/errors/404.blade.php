<x-layouts.empty>
	<div
		class="stack-content grid"
		data-local-color-scheme="dark"
	>
		<div
			class="cover-background opacity-20"
			style="background-image: url({{ Vite::image('404-background.webp') }})"
		></div>

		<div class="relative grid content-center justify-items-center gap-8">
			<x-typography.main-heading>Errore 404</x-typography.main-heading>
			<x-typography.main-subheading>Pagina non trovata</x-typography.main-subheading>
			<x-button
				href="/"
				variant="secondary"
			>
				Torna alla home
			</x-button>
		</div>
	</div>
</x-layouts.empty>
