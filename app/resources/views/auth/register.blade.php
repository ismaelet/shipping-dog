@push('styles')
	<style>

	</style>
@endpush

<x-layouts.auth
	:$title
	welcome-image="auth/register-background.webp"
	welcome-title="<span class='font-light'>Registrati su</span><br>Shipping Dog"
	welcome-description="Registrandoti sulla nostra piattaforma potrai monitorare lo stato dei tuoi ordini, seguire gli
	artisti che ti piacciono, aggiungere articoli ai tuoi preferiti e molto altro. Sei un disegnatore?
	Qui potrai vendere le tue grafiche stampate su tantissimi capi e accessori."
>

	<!-- Form -->
	<x-slot name="formBody">
		<x-form.field>
			<x-label>E-mail</x-label>
			<x-input
				type="email"
				name="email"
				:value="old('email')"
				placeholder="E-mail"
				required
			/>

			@error('email')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</x-form.field>

		<x-form.field>
			<x-label>Password</x-label>
			<x-input
				type="password"
				name="password"
				placeholder="Password"
				maxlength="20"
				required
			/>

			@error('password')
				<x-alerts.error>{{ $message }}</x-alerts.error>
			@enderror
		</x-form.field>

		<x-form.field>
			<x-input
				type="password"
				name="password_confirmation"
				placeholder="Conferma la password"
				maxlength="20"
				required
			/>
		</x-form.field>

		<x-checkbox
			name="privacy"
			text="Ho letto e acconsento al <a class='font-bold text-neutral-700' href='{{ route('support.privacy-policy') }}'>trattamento
                dei dati</a>"
			required
		/>
	</x-slot>

	<x-slot name="formFooter">
		<x-button type="submit">Registrati</x-button>
		<x-button
			href="{{ route('login') }}"
			variant="secondary"
		>Indietro</x-button>
	</x-slot>
</x-layouts.auth>
