@php
	$navigationGroups = [];
@endphp

@pushOnce('styles')
	<style>

	</style>
@endpushOnce

<x-layouts.main>
	<x-dashboard :$navigationGroups>
		<div id="head">

			<h1 class="section-heading mb-12 text-center">Lista vendite</h1>

			<?php

	if (\Legacy\System\Classes\LegacyDB::find('request FROM stores WHERE user_id = ?', [$seller->id])) { ?>

			<div class="request">

				<p class="text">
					Hai già richiesto il pagamento. Se non hai ancora mandato il documento con la richiesta di pagamento compilato
					all'indirizzo <a
						class="txt-sbold txt-c2"
						href="<?= config('custom.contacts.email.url') ?>"
					><?= config('custom.contacts.email') ?></a> ti consigliamo di farlo al più presto.<br><br>
					Se vuoi riscaricare o rigenerare il <strong>PDF</strong> da compilare, puoi cliccare qui sotto.
				</p>

				<div class="mt-8 flex justify-center">
					<a
						class="btn button--auto-width"
						href="<?= route('seller.payment') ?>"
					>Scarica di nuovo il <strong>PDF</strong></a>
				</div>
			</div>

			<?php } else if ($seller->toBePayed() >= 31.25) { ?>

			<div class="request">
				<p class="text">
					Se <strong>non hai una partita IVA e risiedi in Italia</strong> puoi usare il bottone qui sotto per richiedere il
					pagamento di <strong><?= $seller->toBePayedString() ?></strong><br><br>
					Se invece possiedi una partita IVA oppure non risiedi in Italia, ti preghiamo di contattarci all'indirizzo <a
						class="txt-sbold txt-c2"
						href="<?= config('custom.contacts.email.url') ?>"
					><?= config('custom.contacts.email') ?></a> per richiedere il pagamento.
				</p>

				<div class="mt-8 flex justify-center">
					<a
						href="<?= route('seller.payment') ?>"
						class="btn button--auto-width"
						name="request-payment"
					>
						<?= embed_icon('dollar') ?>
						Richiedi il pagamento
					</a>
				</div>
			</div>

			<?php } else { ?>

			<div class="request">

				<p class="text">
					I pagamenti dei guadagni accumulati non sono inviati automaticamente, ma devono essere richiesti cliccando qui
					sotto.
					È possibile richiedere il pagamento se il totale a pagare è superiore a <strong>25,00€ netti</strong> (31,25€
					lordi).
				</p>

				<button
					type="button"
					class="btn button--auto-width place-center mar-t-1"
					disabled
				>
					<?= embed_icon('dollar') ?>
					Richiedi il pagamento
				</button>

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
						<div class="rec_row">
							<div class="rec_cell">
								<p class="rec_lbl">Guadagno:</p>
								<input
									type="text"
									class="input wid-100"
									value="<?= $seller->earnedString() ?>"
									disabled
								>
							</div>

							<div class="rec_cell">
								<p class="rec_lbl inlb">Bonus: </p>
								<x-help.info>Per ogni capo venduto riceverai un ulteriore <strong>10%</strong> del guadagno come bonus. Puoi
									usare
									questo bonus per pagare le spese di spedizione quando acquisti sul nostro sito, oppure per comprare prodotti
									del
									tuo negozio senza pagarli.</x-help.info>
								<input
									type="text"
									class="input wid-100"
									value="<?= $seller->creditString() ?>"
									disabled
								>
							</div>
						</div>
						<div class="rec_row">
							<div class="rec_cell">
								<p class="rec_lbl">PAGATO:</p>
								<input
									type="text"
									class="input wid-100"
									value="<?= $seller->payedString() ?>"
									disabled
								>
							</div>

							<div class="rec_cell">
								<p class="rec_lbl">ALTRO:</p>
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
			class="mar-t-3"
		>

			@if ($sales->count())

				<div class="list-cont">
					@if ($sort == 'designs')
						@include('seller-dashboard.sales.designs')
					@elseif ($sort == 'time')
						@include('seller-dashboard.sales.history')
					@endif
				</div>
			@else
				<p class="pad-t-3 text-center">Ancora non hai realizzato nessuna vendita</p>

			@endif

		</div>
	</x-dashboard>
	</x-layouts.empty>
