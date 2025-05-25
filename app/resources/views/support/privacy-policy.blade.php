<x-layouts.form
	:$title
	:$locationTag
>

	<x-slot name="body">

		<p class="leading-7">
			Noi di Shipping Dog trattiamo i dati personali degli utenti adottando le opportune misure di sicurezza volte ad
			impedire l’accesso, la divulgazione, la modifica o la distruzione non autorizzate. Quindi i tuoi dati non verranno
			mai
			passati, scambiati né tanto meno venduti a qualsiasi realtà esterna alla nostra, ad eccezione di paypal che processa
			i
			pagamenti ed eventuali corrieri che provvedono alla consegna.
		</p>

	</x-slot>

	<x-slot name="footer">

		<x-button
			href="{{ route('home') }}"
			class=""
		>Indietro</x-button>

	</x-slot>
</x-layouts.form>
