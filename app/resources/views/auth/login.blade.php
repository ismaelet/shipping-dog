@pushOnce('styles')
	<style>
		main .input {
			padding-inline: 1.5em;
			border-radius: var(--border-radius-1000)
		}
	</style>
@endpushOnce

<x-layouts.auth
	:$title
	welcome-title="<span class='font-light'>Benvenuto su</span><br>Shipping Dog"
	welcome-image="auth/login-background.webp"
	welcome-description="Entrando nella nostra piattaforma potrai monitorare lo stato dei tuoi ordini, seguire gli artisti
	che ti piacciono e molto altro. Sei un disegnatore? Qui
	potrai vendere le tue grafiche stampate su tantissimi capi e accessori."
>

	<x-slot name="formBody">
		<x-input
			type="hidden"
			name="url"
			:value="old('url')"
		/>
		<x-input
			type="text"
			name="login"
			:value="old('login')"
			placeholder="Username o e-mail"
			required
		/>
		<x-input
			type="password"
			name="password"
			placeholder="Password"
			required
		/>

		@if ($errors->any())
			<ul class="grid grid-cols-1 gap-2">
				@foreach ($errors->all() as $error)
					<x-inputs.warning>{{ $error }}</x-inputs.warning>
				@endforeach
			</ul>
		@endif

		@if (session('status'))
			<x-alerts.success>
				{{ session('status') }}
			</x-alerts.success>
		@endif

		<div class="flex items-center justify-between">
			<x-checkbox
				name="remember"
				text="Resta connesso"
				checked
			/>

			<a
				href="{{ route('password.request') }}"
				class="text-right transition-colors hover:text-neutral-700"
			>
				<p class="">Hai dimenticato la password?</p>
			</a>
		</div>
	</x-slot>

	<x-slot name="formFooter">
		<x-button type="submit">Log In</x-button>
		<x-button
			:href="route('register')"
			variant="secondary"
		>Registrati</x-button>
	</x-slot>
</x-layouts.auth>
