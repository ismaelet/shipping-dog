@pushOnce('scripts')
	<script>
		function showSaveButton(element) {
			const panel = element.closest('[data-panel]')
			const saveButtonContainer = panel.querySelector('[data-save-button-container]')

			saveButtonContainer.dataset.status = "active"
		}

		async function updateSaveButton(button, response) {
			const saveButtonContainer = button.closest('[data-save-button-container]')
			saveButtonContainer.dataset.status = "loading"

			response = await response

			if (response) {
				saveButtonContainer.dataset.status = "saved"
			} else {
				saveButtonContainer.dataset.status = "error"
			}
		}
	</script>
@endpushOnce

<div
	class="group grid gap-4"
	data-save-button-container
	data-status="inactive"
	x-data
>
	<x-inputs.alert-success class="hidden group-data-[status=saved]:flex">
		{!! embed_icon('correct') !!}
		Impostazioni salvate
	</x-inputs.alert-success>

	<x-inputs.alert-error class="hidden group-data-[status=error]:flex">
		{!! embed_icon('close') !!}
		Errore di salvataggio
	</x-inputs.alert-error>

	<x-inputs.button
		class="hidden group-data-[status=active]:flex group-data-[status=error]:flex"
		data-save-button
		@click="updateSaveButton($event.target, {{ $function }}($event.target))"
	>
		{!! embed_icon('save') !!}
		Salva
	</x-inputs.button>

	<span class="hidden w-12 justify-self-center group-data-[status=loading]:block">{!! embed_icon('loading') !!}</span>
</div>
