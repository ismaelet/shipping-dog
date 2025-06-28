<div class="contents">
	@if ($visible)
		<section class="cart-panel surface">
			<x-heading.component>Credito</x-heading.component>

			<p>Puoi usare i tuoi guadagni per acquistare prodotti del tuo negozio o pagare le spese di spedizione.<br><br>Questa
				funzionalità
				non può essere usata
				insieme ad un codice sconto ed è possibile usarla solo se si ha abbastanza credito da coprire l'intero prezzo
				del prodotto o delle spedizioni.</p>

			<p class="text-center">Hai usato <span class="text-strong">{{ price($usedCredit) }} /
					{{ price($totalCredit) }}</span></p>

			<div class="bg-surface-500 h-2 rounded-full">
				<div
					class="bg-primary h-full rounded-[inherit] transition-all"
					style="width: {{ round(($usedCredit / $totalCredit) * 100) }}%"
				></div>
			</div>

		</section>
	@endif
</div>
