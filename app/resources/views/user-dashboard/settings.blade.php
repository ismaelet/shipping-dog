<x-user-dashboard :$title>
	<x-user-dashboard.panel
		:icon="'settings'"
		:$title
	>

		<div class="grid gap-12">
			{{-- Profile settings --}}
			<livewire:user-dashboard.settings.profile />

			{{-- Password settings --}}
			<livewire:user-dashboard.settings.password />

			{{-- Profile icon --}}
			<livewire:user-dashboard.settings.upload-icon />

			{{-- Delete account --}}
			<form
				class="grid grid-cols-1 gap-8"
				method="POST"
				action="{{ route('user.destroy') }}"
			>
				@csrf
				@method('DELETE')

				<x-typography.component-heading-small class="dashboard-heading">Elimina
					l'account</x-typography.component-heading-small>

				<div class="grid grid-cols-1 gap-4">
					<p>Clicca qui sotto se vuoi cancellare definitivamente il tuo account. <span
							class="text-strong text-error-400">L'operazione NON
							può essere
							annullata.</span></p>
				</div>

				<div class="footer flex justify-center">
					<x-button
						type="submit"
						confirmation="Sei sicuro di voler eliminare il profilo? Non potrà essere ripristinato."
						color="danger"
						variant="secondary"
					>
						{!! embed_icon('delete') !!}
						Elimina l'account
					</x-button>
				</div>
			</form>
		</div>

	</x-user-dashboard.panel>
</x-user-dashboard>
