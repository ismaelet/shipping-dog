@php
	use App\Legacy\System\DB;

	$navigationGroups = [];
@endphp

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/sales-index.css') }}"
	>
@endpushOnce

<x-layouts.seller-dashboard>
	<div class="grid grid-cols-1 gap-8">
		<livewire:seller.activation-request :$seller />

		<div class="dashboard-panel surface">
			<div id="head">

				<x-heading.component>Lista vendite</x-heading.component>

				<?php

	if (DB::find('request FROM sellers WHERE user_id = ?', [$seller->id])) { ?>

				<div class="request">

					<p class="text">
						Hai già richiesto il pagamento. Se non hai ancora mandato il documento con la richiesta di pagamento compilato
						all'indirizzo <a
							class="txt-sbold txt-c2"
							href="<?= config('custom.contacts.email.url') ?>"
						><?= config('custom.contacts.email.label') ?></a> ti consigliamo di farlo al più presto.<br><br>
						Se vuoi riscaricare o rigenerare il <strong>PDF</strong> da compilare, puoi cliccare qui sotto.
					</p>

					<div class="mt-8 flex justify-center">
						<x-button
							class="button--auto-width"
							href="{{ route('seller-dashboard.payment') }}"
						>Scarica di nuovo il <span class="text-strong">PDF</span></x-button>
					</div>
				</div>

				<?php } else if ($seller->toBePayed() >= 31.25) { ?>

				<div class="request">
					<p class="text">
						Se <strong>non hai una partita IVA e risiedi in Italia</strong> puoi usare il bottone qui sotto per richiedere il
						pagamento di <strong><?= $seller->toBePayedString() ?></strong><br><br>
						Se invece possiedi una partita IVA oppure non risiedi in Italia, ti preghiamo di contattarci all'indirizzo <a
							class="text-link"
							href="<?= config('custom.contacts.email.url') ?>"
						><?= config('custom.contacts.email.label') ?></a> per richiedere il pagamento.
					</p>

					<div class="mt-8 flex justify-center">
						<x-button
							href="{{ route('seller-dashboard.payment') }}"
							class="button--auto-width"
							name="request-payment"
						>
							{!! embed_icon('dollar') !!}
							Richiedi il pagamento
						</x-button>
					</div>
				</div>

				<?php } else { ?>

				<div class="request">

					<p class="text">
						I pagamenti dei guadagni accumulati non sono inviati automaticamente, ma devono essere richiesti cliccando qui
						sotto.
						È possibile richiedere il pagamento se il totale a pagare è superiore a <span class="text-strong">25,00€
							netti</span> (31,25€
						lordi).
					</p>

					<x-button
						type="button"
						class="button--auto-width"
						disabled
					>
						{!! embed_icon('dollar') !!}
						Richiedi il pagamento
					</x-button>

				</div>

				<?php } ?>

				<form method="GET">

					<section id="recap">

						<!-- Sorting method -->
						<div id="recap-sort">

							<input
								type="hidden"
								name="id"
								value="<?= $seller->id ?>"
							>
							<input
								type="hidden"
								id="sort-inp"
								name="sort"
								value="<?= $sort ?>"
							>
							<input
								type="hidden"
								id="year-inp"
								name="year"
								value="<?= $currentYear ?>"
							>

							<h1 class="sort-txt">Visualizza:</h1>

							<p
								class="sort-link clickable <?= $sort == 'designs' ? 'active' : '' ?>"
								data-sort="designs"
								onclick="$('#sort-inp').val($(this).data('sort')); $('#search-btn').click();"
							>
								Grafiche
							</p>

							<p
								class="sort-link clickable <?= $sort == 'time' ? 'active' : '' ?>"
								data-sort="time"
								onclick="$('#sort-inp').val($(this).data('sort')); $('#search-btn').click();"
							>
								Cronologia
							</p>

							<button
								type="submit"
								id="search-btn"
								class="hidden"
							></button>

						</div>

						<!-- Recap -->
						<div
							id="recap-data"
							class="gap1 grid"
						>
							<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
								<div class="rec_cell">
									<p class="rec_lbl">Guadagno:</p>
									<input
										type="text"
										class="input wid-100"
										value="<?= $seller->earnedString() ?>"
										disabled
									>
								</div>

								{{-- <div class="rec_cell">
								<div class="align-center flex gap-4">
									<p class="rec_lbl inlb">Bonus: </p>
									<x-help.info>Per ogni capo venduto riceverai un ulteriore <strong>10%</strong> del guadagno come bonus. Puoi
										usare
										questo bonus per pagare le spese di spedizione quando acquisti sul nostro sito, oppure per comprare prodotti
										del
										tuo negozio senza pagarli.</x-help.info>
								</div>
								<input
									type="text"
									class="input wid-100"
									value="<?= $seller->creditString() ?>"
									disabled
								>
							</div> --}}
								<div class="rec_cell">
									<p class="rec_lbl">PAGATO:</p>
									<input
										type="text"
										class="input wid-100"
										value="<?= $seller->payedString() ?>"
										disabled
									>
								</div>
							</div>
							<div class="grid grid-cols-1 gap-4 md:grid-cols-2">

								<div class="rec_cell">
									<div class="align-center flex gap-4">
										<p class="rec_lbl inlb">Altro:</p>
										<x-help.info>Qui figura il totale usato per acquistare prodotti dal proprio negozio, usando il credito
											accoumulato, oppure altri pagamenti di natura varia.</x-help.info>
									</div>
									<input
										type="text"
										class="input wid-100"
										value="<?= $seller->otherTransferAmountString() ?>"
										disabled
									>
								</div>

								<div class="rec_cell">
									<p class="rec_lbl">DA PAGARE:</p>
									<input
										type="text"
										class="input wid-100 text-primary"
										style="font-weight: bold;"
										value="<?= $seller->toBePayedString() ?>"
										disabled
									>
								</div>
							</div>
						</div>

					</section>
				</form>

			</div>

			<div
				id="body"
				class="mar-t-3 grid grid-cols-1 gap-8"
			>

				@if ($sales->count())

					@if ($sort == 'designs')
						@include('seller-dashboard.sales.designs')
					@elseif ($sort == 'time')
						<div class="list-cont surface px-(--content-spacing)">
							@include('seller-dashboard.sales.history')
						</div>
					@endif
				@else
					<p class="pad-t-3 text-center">Ancora non hai realizzato nessuna vendita</p>

				@endif

			</div>
		</div>
	</div>
</x-layouts.seller-dashboard>
