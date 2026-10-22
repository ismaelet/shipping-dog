<form
	id="upload-image-light"
	action="{{ route('designs.upload-image', [$design, 'type' => 'light']) }}"
	method="POST"
	class="surface grid gap-4 p-6"
	enctype="multipart/form-data"
>
	@csrf

	<div>
		<x-heading.component>Grafica chiara</x-heading.component>
		<p>grafica utilizzata su capi chiari</p>
	</div>

	@if ($design->custom)
		<x-help.info>
			Questa immagine è usata solo nelle sezioni dove compare la grafica senza un mockup. Essendo questo un prodotto
			particolare i mockup non vengono creati automaticamente.
		</x-help.info>
	@endif

	<div
		class="grid aspect-square grid-rows-1 border"
		style="background-color: <?= $design->color ?>;"
		data-color-background
	>
		<x-inputs.image-replacer
			:image="FileHelper::storageAsset('designs/' . $design->id . '.png')"
			auto-submit="true"
			name="file_light"
		/>
	</div>

	<x-inputs.group>
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
				<x-icon-upload />
				Ricarica grafica
			</x-button>
		</div>

		@error('file_light')
			<x-alerts.error>{{ $message }}</x-alerts.error>
		@enderror
	</x-inputs.group>
</form>
