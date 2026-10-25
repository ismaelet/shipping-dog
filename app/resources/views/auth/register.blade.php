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

		<div class="flex flex-wrap items-center gap-1">
			<x-checkbox
				name="privacy"
				text="Ho letto e acconsento al"
				required
			/>
			<a
				class='text-link'
				href='{{ route('support.privacy-policy') }}'
			>trattamento
				dei dati</a>
		</div>
	</x-slot>

	<x-slot name="formFooter">
		<div class="grid w-full max-w-[min(30rem,100%)] grid-cols-1 gap-4">
			<x-button type="submit">Registrati</x-button>

			<div class="grid grid-cols-[1fr_auto_1fr] items-center gap-4 text-center">
				<span class="border-neutral-400"></span>
				<p>oppure</p>
				<span class="border-neutral-400"></span>
			</div>

			<x-button
				href="{{ route('auth.google.redirect') }}"
				variant="secondary"
			>
				<x-icon-google />
				Registrati con Google
			</x-button>

			<a
				href="{{ route('login') }}"
				variant="secondary"
				class="hover:text-heading-400 text-center transition-colors"
			>
				Hai già un account? Accedi
			</a>
		</div>
	</x-slot>
</x-layouts.auth>
