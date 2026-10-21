<x-user-dashboard.settings-section
	title="Dati profilo"
	text="Le informazioni associate al tuo account."
	icon="user"
	tone="primary"
>
	<form
		class="grid gap-5 md:grid-cols-2"
		wire:submit="save"
	>
		@session('success')
			<x-alerts.success class="md:col-span-2">{{ session('success') }}</x-alerts.success>
		@endsession

		<div class="grid content-start gap-2">
			<x-label>Nome</x-label>
			<x-input
				class="w-full"
				type="text"
				wire:model="firstName"
				placeholder="Nome"
			/>

			@error('firstName')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>

		<div class="grid content-start gap-2">
			<x-label>Cognome</x-label>
			<x-input
				class="w-full"
				type="text"
				wire:model="lastName"
				placeholder="Cognome"
			/>

			@error('lastName')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>

		<div class="grid content-start gap-2 md:col-span-2">
			<x-label>E-mail</x-label>
			<x-input
				class="w-full"
				type="email"
				wire:model="email"
				placeholder="E-mail"
				required
			/>

			@error('email')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</div>

		<div class="md:col-span-2">
			<x-button
				type="submit"
				size="small"
			>Salva modifiche</x-button>
		</div>
	</form>
</x-user-dashboard.settings-section>
