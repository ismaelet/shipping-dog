<div class="contents">
	@if ($visible)
		@if ($seller->activation_request_sent)
			<div class="grid justify-items-center gap-4 rounded-sm border border-green-500 px-4 py-2">
				<p class="text-green-500">La tua richiesta di attivazione è stata inviata con successo e verrà elaborata entro 24h.
				</p>
			</div>
		@elseif ($seller->products->count())
			<div class="grid justify-items-center gap-4 rounded-sm border border-red-500 px-4 py-2">
				<p class="text-red-500">Il tuo negozio non è ancora attivo. Quando sei pronto, invia la
					richiesta di
					attivazione. cliccando qui sotto:</p>

				<x-button
					wire:loading.remove
					wire:click="sendActivationRequest"
				>Richiedi attivazione</x-button>
			</div>
		@else
			<div class="grid justify-items-center gap-4 rounded-sm border border-red-500 px-4 py-2">
				<p class="text-red-500">Il tuo negozio non è ancora attivo. Per favore, aggiungi almeno un design per poterlo
					pubblicare.</p>

				<x-button href="{{ route('seller-dashboard.portfolio.add') }}">Carica un design</x-button>
			</div>
		@endif
	@endif
</div>
