<form
	class="grid grid-cols-1 gap-8"
	method="POST"
	wire:submit="save"
>
	<x-heading.component-small class="dashboard-heading">Dati profilo</x-heading.component-small>

	<div class="form-fields">
		@session('success')
			<x-alerts.success>{{ session('success') }}</x-alerts.success>
		@endsession

		<div class="form-field">
			<x-label>Nome e cognome:</x-label>
			<div class="grid gap-[inherit] md:grid-cols-2">
				<x-input
					type="text"
					wire:model="firstName"
					value="{{ $firstName }}"
					placeholder="Nome"
				/>
				<x-input
					type="text"
					wire:model="lastName"
					value="{{ $lastName }}"
					placeholder="Cognome"
				/>
			</div>

			@error('firstName')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror

			@error('lastName')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>

		<div class="form-field">
			<x-label>E-mail:</x-label>
			<x-input
				type="email"
				wire:model="email"
				value="{{ $email }}"
				placeholder="email"
				required
			/>

			@error('email')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>
	</div>

	<div class="footer flex justify-center">
		<x-button type="submit">Modifica</x-button>
	</div>
</form>
