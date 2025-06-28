<form
	class="grid grid-cols-1 gap-8"
	method="POST"
	wire:submit="save"
>
	<x-heading.component-small class="dashboard-heading">Modifica la
		password</x-heading.component-small>

	<div class="form-fields">
		@session('success')
			<x-alerts.success>{{ session('success') }}</x-alerts.success>
		@endsession

		<div class="form-field">
			<x-label>Password attuale:</x-label>
			<x-input
				type="password"
				wire:model="current_password"
				placeholder="Password attuale"
				required
			/>

			@error('current_password')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>

		<div class="form-field">
			<x-label>Nuova password:</x-label>
			<div class="grid gap-[inherit] md:grid-cols-2">
				<x-input
					type="password"
					wire:model="new_password"
					placeholder="Nuova password"
					required
				/>
				<x-input
					type="password"
					wire:model="new_password_confirmation"
					placeholder="Conferma nuova password"
					required
				/>
			</div>

			@error('new_password')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror

			@error('new_password_confirmation')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>
	</div>

	<div class="footer flex justify-center">
		<x-button type="submit">Modifica</x-button>
	</div>
</form>
