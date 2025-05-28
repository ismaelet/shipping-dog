<x-layouts.form
	:$title
	:$locationTag
	method="POST"
	action="{{ route('orders.find') }}"
>

	<x-slot:body>
		<div class="form-fields">
			<div class="form-field">
				<x-label text="Indirizzo email" />
				<x-input
					type="email"
					name="email"
					value="{{ old('email') }}"
					placeholder="Indirizzo email:"
					required
				/>
			</div>

			<div class="form-field">
				<x-label text="Numero ordine:" />
				<x-input
					type="text"
					name="id"
					value="{{ old('id') }}"
					placeholder="Numero ordine"
					required
				/>
			</div>

			@session('error')
				<x-alerts.error>
					{{ session('error') }}
				</x-alerts.error>
			@endsession
		</div>

		<p class="mar-t-2">
			Il numero dell'ordine è indicato nella email di conferma ricevuta dal nostro sito e <span
				class="text-strong">NON</span> è il numero
			di
			ricevuta indicato nell'email ricevuta da PayPal. L'indirizzo email deve essere quello inizialmente utilizzato per
			effettuare l'ordine.
		</p>

	</x-slot>

	<x-slot:footer>

		<x-button type="submit">Ricerca</x-button>

	</x-slot:body>
</x-layouts.form>
