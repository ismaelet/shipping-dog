<x-layouts.form
	:$title
	:$location
	size="lg"
>

	<x-slot name="body">
		<div class="">
			<div class="grid grid-cols-[10rem_1fr_1fr] items-center gap-4">

				@foreach ($changeRequest->items as $item)
					<div class="col-span-full grid grid-cols-subgrid items-center rounded text-center dark:bg-neutral-200">
						<div class="item-cell item-pic_cell">
							<div class="h-full">
								<img
									src="{{ $item->old_order_item->image() }}"
									class="h-full rounded object-cover"
								>
							</div>
						</div>

						<div class="item-cell item-name_cell">
							<h4 class="text-lg">{{ $item->old_order_item->product_with_trashed->name }}</h4>

							<p class="text-small-paragraph">
								{{ $item->old_order_item->description() }}
							</p>
						</div>

						<div class="item-cell item-new_cell">

							@if ($item->is_change)
								<h4 class="font-semibold text-neutral-700">Nuova taglia:</h4>
								<p class="">{{ $item->new_size->name }}</p>
							@else
								<p class="font-semibold text-neutral-700">RESO</p>
							@endif

						</div>
					</div>
				@endforeach
			</div>

			@if (!$changeRequest->isRefundOnly())
				<x-alert
					type="success"
					class="my-4"
				>Grazie per aver effetuato il pagamento. Procedi ora con la spedizione del reso all'indirizzo indicato nelle
					istruzioni sottostanti.</x-alert>
			@endif

			<h3 class="mb-2 mt-8 text-lg font-semibold text-neutral-700">Istruzioni</h3>
			<p class="txt info">
				@if ($changeRequest->isRefundOnly())
					<span class="font-semibold text-neutral-700">1.</span> Spedisci il pacco con il reso con il metodo di spedizione
					che
					preferisci (tramite Poste o
					qualsiasi corriere) all'indirizzo:<br><br>

					Shipping Dog SNC<br>
					Piazza Scopoli, 9<br>
					38033 Cavalese (TN)<br><br>

					<span class="font-semibold text-neutral-700">2.</span> Il costo degli articoli sarà rimborsato entro <span
						class="font-semibold text-neutral-700"
					>un giorno lavorativo</span> dalla
					ricezione del reso.
				@else
					<span class="font-semibold text-neutral-700">1.</span> Grazie per aver terminato la procedura di cambio. Metteremo
					in produzione il nuovo capo, che
					sarà spedito in
					{{ $changeRequest->new_order->shipping_method->minimumWorkingDays(false) }}-{{ $changeRequest->new_order->shipping_method->maximumWorkingDays(false) }}
					giorni
					lavorativi tramite <span class="font-semibold text-neutral-700">corriere
						GLS</span>.<br><br>
					<span class="font-semibold text-neutral-700">2.</span> Quando il nuovo capo sarà spedito riceverai una e-email da
					parte del corriere la data di
					consegna stimata.<br><br>
					<span class="font-semibold text-neutral-700">3.</span> Per rendere il capo vecchio è sufficiente darlo al corriere
					quando ti consegnerà il capo nuovo.
				@endif
			</p>

			<h3 class="mb-2 mt-8 text-lg font-semibold text-neutral-700">Note</h3>
			<p class="txt info">
				@if ($changeRequest->isRefundOnly())
					<span class="font-semibold text-neutral-700">-</span> Gli articoli da rendere possono essere messi in una scatola o
					in una busta. Non è necessario
					usare la confezione originale.<br><br>
					<span class="font-semibold text-neutral-700">-</span> Verrà rimborsato <span
						class="font-semibold text-neutral-700">SOLO</span> il costo degli articoli, non delle spese di spedizione
					pagate al momento dell'acquisto o per effettuare il reso.<br><br>
					<span class="font-semibold text-neutral-700">-</span> Se vuoi effettuare il reso perché il prodotto presenta
					qualche
					difetto o non corrisponde a ciò
					che hai ordinato, invece di seguire questa procedura contattaci su <a
						href="{{ config('custom.contacts.email.url') }}"
					>{{ config('custom.contacts.email.label') }}</a> e cercheremo di risolvere il
					problema.
				@else
					<span class="font-semibold text-neutral-700">-</span> Gli articoli da rendere possono essere messi in una scatola o
					in una busta. Non è necessario
					usare la confezione originale.<br><br>
				@endif
			</p>

		</div>
	</x-slot>

	<x-slot name="footer">
		@if ($changeRequest->isRefundOnly())
			<x-button
				type="submit"
				formaction="{{ route('support.refund.cancel', $changeRequest) }}"
				variant="secondary"
				color="danger"
			>Cancella la richiesta di reso</x-button>
		@else
			<button
				type="submit"
				formaction="{{ route('support.refund.clear') }}"
				class="text-secondary-400 cursor-pointer"
			>Effettua un altro cambio</button>
		@endif
	</x-slot>
</x-layouts.form>
