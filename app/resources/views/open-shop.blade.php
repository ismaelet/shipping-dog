<x-layouts.form
	:$title
	:$locationTag
>

	<x-slot name="body">
		<p class="txt-jus pad-t-1">Iniziare a vendere le tue grafiche su <span class="text-strong">Shipping Dog</span> è facile,
			veloce,
			completamente gratuito e non è necessario avere partita IVA o nessun altro requisito.<br><br>Compila i campi qui
			sotto
			e crea, senza impegno, il tuo negozio. All'inizio il negozio sarà visibile solo a te, ma quando caricherai le prime
			grafiche lo valuteremo e pubblicheremo.</p>

		<x-input
			type="text"
			name="name"
			label="Nome del negozio"
			placeholder="Nome del negozio"
			required
			autofocus
			maxlength="30"
			required
		/>

		<x-textarea
			name="description"
			label="Descrizione del negozio"
			placeholder="Descrizione del negozio (opzionale)"
			rows="3"
			maxlength="100"
		/>

		<x-checkbox
			text="Ho letto e accetto i <a class='text-link' href='{{ route('support.sale-terms') }}'>termini di vendita</a>"
			name="terms"
			id="terms"
			required
		/>

	</x-slot>

	<x-slot name="footer">

		<x-button
			type="submit"
			id="submit"
			name="submit"
			class=""
		>Invia richiesta</x-button>

	</x-slot>
</x-layouts.form>
