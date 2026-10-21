<x-user-dashboard.settings-section
	title="Modifica la password"
	text="Scegli una password unica e difficile da indovinare."
	icon="lock"
	tone="detail-1"
>
	<form
		class="grid gap-5 md:grid-cols-2"
		wire:submit="save"
	>
		@session('success')
			<x-alerts.success class="md:col-span-2">{{ session('success') }}</x-alerts.success>
		@endsession

		<div class="grid content-start gap-2 md:col-span-2">
			<x-label>Password attuale</x-label>
			<x-input
				class="w-full"
				type="password"
				wire:model="current_password"
				placeholder="Inserisci la password attuale"
				required
			/>

			@error('current_password')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>

		<div class="grid content-start gap-2">
			<x-label>Nuova password</x-label>
			<x-input
				class="w-full"
				type="password"
				wire:model="new_password"
				placeholder="Almeno 8 caratteri"
				required
			/>

			@error('new_password')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>

		<div class="grid content-start gap-2">
			<x-label>Conferma password</x-label>
			<x-input
				class="w-full"
				type="password"
				wire:model="new_password_confirmation"
				placeholder="Ripeti la nuova password"
				required
			/>

			@error('new_password_confirmation')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>

		<div class="md:col-span-2">
			<x-button
				type="submit"
				size="small"
			>Aggiorna password</x-button>
		</div>
	</form>
</x-user-dashboard.settings-section>
