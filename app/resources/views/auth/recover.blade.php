<x-layouts.form :title="$title">

	<x-slot name="body">

		@if (!session('submitted') || session('not_found'))

			{{-- Show instructions to recover the account --}}
			<p class="text-justify">
				Inserisci l'indirizzo email associato al tuo account e ti manderemo un'email con le istruzioni per
				resettare la password.
			</p>

			{{-- Email input --}}
			<x-form.input
				type="email"
				name="email"
				placeholder="Indirizzo email"
				:value="session('email') ?: ''"
				required
			/>

			{{-- If the form hasn't been submitted or the email is not found --}}
			@session('not_found')
				{{-- Show error message if email is not found --}}
				<x-form.input-warning>
					Non abbiamo trovato nessun account associato all'indirizzo email indicato
				</x-form.input-warning>
			@endsession
		@else
			{{-- If the email is found --}}
			<p class="text-justify">Abbiamo mandato un'email con le istruzioni per resettare la password
				all'indirizzo qui sotto. Se non dovessi ricevere l'email entro qualche minuto, controlla la cartella di
				spam.
			</p>

			<p class="text-center text-lg font-bold text-neutral-700">{{ session('email') }}</p>
		@endif

	</x-slot>

	<x-slot name="footer">

		@if (!session('submitted') || session('not_found'))
			{{-- If the form hasn't been submitted or the email is not found --}}
			<x-button type="submit">Recupera i dati</x-button>
		@endif

		<x-button
			variant="secondary"
			:href="route('login')"
		>Indietro</x-button>

	</x-slot>

</x-layouts.form>
