<div @class([
	'seller-activation-banner border-primary-400/20 bg-primary-400/10 grid justify-items-center gap-4 rounded-3xl border px-6 py-6 text-center xl:col-span-2',
	'hidden' => !$visible,
])>
	@if ($visible)
		<div class="bg-primary-400/15 text-primary-300 grid size-10 place-items-center rounded-full">
			<x-icon-shop class="size-5" />
		</div>

		@if ($seller->activation_request_sent)
			<p
				role="status"
				class="font-heading text-neutral-heading text-lg font-semibold italic"
			>
				Richiesta di attivazione inviata
			</p>
			<p class="text-neutral-heading/50 text-sm">
				La tua richiesta verrà elaborata entro 24 ore.
			</p>
		@elseif ($seller->products->count())
			<x-heading variant="article-small">
				Il tuo negozio non è ancora attivo
			</x-heading>
			<p class="text-neutral-heading/50 max-w-140 text-sm">
				Controlla che le informazioni del negozio siano complete e invia la richiesta quando sei pronto per iniziare a
				vendere.
			</p>

			<x-button
				wire:loading.remove
				wire:click="sendActivationRequest"
				size="small"
			>Richiedi attivazione</x-button>
		@else
			<p class="font-heading text-neutral-heading text-lg font-semibold italic">
				Il tuo negozio non è ancora attivo
			</p>
			<p class="text-neutral-heading/50 text-sm">
				Aggiungi almeno un design prima di richiedere l'attivazione del negozio.
			</p>

			<x-button
				href="{{ route('seller-dashboard.portfolio.add') }}"
				size="small"
			>Carica un design</x-button>
		@endif
	@endif
</div>
