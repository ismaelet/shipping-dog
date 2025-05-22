<x-layouts.form :title="$title">

	<x-slot name="body">

		@if (session('submitted'))
			<p class="text-center text-lg font-semibold">Password cambiata con successo!</p>
		@elseif (session('error'))
			<x-form.alert type="error">Il link di rest non è valido o è scaduto</x-form.alert>
		@else
			<p class="">
				Inserisci qui sotto la nuova password desiderata:
			</p>

			<x-form.input
				type="hidden"
				name="token"
				:value="$token"
			/>
			<x-form.input
				type="hidden"
				name="email"
				:value="$email"
			/>
			<x-form.input
				type="password"
				name="password"
				placeholder="Nuova password"
				required
			/>
			<x-form.input
				type="password"
				name="password_confirmation"
				placeholder="Conferma la nuova password"
				required
			/>

			@error('password')
				<x-form.input-warning>{{ $message }}</x-form.input-warning>
			@enderror
		@endif

	</x-slot>

	<x-slot name="footer">

		@if (session('error'))
			<x-button
				variant="secondary"
				:href="route('auth.recover')"
			>{{ __('Richiedi un nuovo codice') }}</x-button>
		@else
			@if (!session('submitted'))
				{{-- If the form hasn't been submitted or the email is not found --}}
				<x-button type="submit">Reimposta la password</x-button>
			@endif

			<x-button
				variant="secondary"
				:href="route('login')"
			>{{ __('Torna al login') }}</x-button>
		@endif

	</x-slot>

</x-layouts.form>
