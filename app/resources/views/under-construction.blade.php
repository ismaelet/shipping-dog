<x-layouts.empty>
	<div
		class="stack-content grid"
		data-local-color-scheme="dark"
	>
		<div
			class="cover-background opacity-20"
			style="background-image: url({{ Vite::image('404-background.webp') }})"
		></div>

		<div class="relative grid content-center justify-items-center gap-8 text-center">
			<x-typography.main-heading>Nuove funzionalità<br>in arrivo</x-typography.main-heading>
			<x-typography.main-subheading class="max-w-[50ch]">Stiamo aggiornando la pagina. Sarà nuovamente disponibile il prima
				possibile. Ci
				scusiamo per il disagio causato.</x-typography.main-subheading>
			<x-button
				href="/"
				variant="secondary"
			>
				Torna alla home
			</x-button>
		</div>
	</div>
</x-layouts.empty>
