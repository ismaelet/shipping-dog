<x-layouts.form
	:$title
	action="{{ route('password.email') }}"
>

	<x-slot name="body">

		@if (!session('status'))
			<p class="text-justify">
				Inserisci l'indirizzo email associato al tuo account e ti manderemo un'email con le istruzioni per
				resettare la password.
			</p>

			<x-input
				type="email"
				name="email"
				placeholder="Indirizzo email"
				:value="old('email')"
				required
			/>

			@error('email')
				<x-alerts.error :$message />
			@enderror
		@else
			{{-- If the email is found --}}
			<p class="text-justify">Abbiamo mandato un'email con le istruzioni per resettare la password
				all'indirizzo qui sotto. Se non dovessi ricevere l'email entro qualche minuto, controlla la cartella di
				spam.
			</p>

			<p class="text-neutral-heading text-center text-lg font-bold">{{ old('email') }}</p>
		@endif

	</x-slot>

	<x-slot name="footer">

		@if (!session('status'))
			<x-button type="submit">Recupera i dati</x-button>
		@endif

		<x-button
			variant="secondary"
			href="{{ route('login') }}"
		>Indietro</x-button>

	</x-slot>

</x-layouts.form>
