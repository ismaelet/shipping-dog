<x-layouts.form
	:$title
	:$location
>

	<x-slot name="body">
		<p class="mb-4">
			Per effettuare un cambio di taglia o un rimborso compila i campi qui sotto.<br><br>
			<i>* Il numero dell'ordine è indicato nella email di conferma ricevuta dal nostro sito e <span
					class="text-strong">NON</span> è il numero
				di ricevuta indicato nell'email ricevuta da PayPal.<br><br>* L'indirizzo email deve essere quello inizialmente
				utilizzato per
				effettuare l'ordine.</i>
		</p>

		<div class="form-fields">
			<div class="form-field">
				<x-input
					type="email"
					name="email"
					value="{{ old('email') }}"
					placeholder="Indirizzo email"
					required
				/>
			</div>

			<div class="form-field">
				<x-input
					type="text"
					name="order_id"
					value="{{ old('order_id') }}"
					placeholder="Numero dell'ordine"
					required
				/>
			</div>

			@session('error')
				<x-alerts.error :message="session('error')" />
			@endsession
		</div>

	</x-slot>

	<x-slot name="footer">

		<x-button type="submit">Cerca</x-button>

	</x-slot>
</x-layouts.form>
