{% block content %}

<div id="head">

	<h1 class="section-heading text-center mb-12">Lista vendite</h1>

	<?php

	if (\Legacy\System\Classes\LegacyDB::find('request FROM stores WHERE user_id = ?', [$oldSeller->id])) { ?>

		<div class="request">

			<p class="text">
				Hai già richiesto il pagamento. Se non hai ancora mandato il documento con la richiesta di pagamento compilato all'indirizzo <a class="txt-sbold txt-c2" href="<?= sys_config('contact-email-link') ?>"><?= sys_config('contact-email') ?></a> ti consigliamo di farlo al più presto.<br><br>
				Se vuoi riscaricare o rigenerare il <strong>PDF</strong> da compilare, puoi cliccare qui sotto.
			</p>

			<a class="btn button--auto-width" href="<?= client_url('/manage/payments/request') ?>">Scarica di nuovo il <strong>PDF</strong></a>

		</div>

	<?php } else if ($oldSeller->toPay >= 31.25) { ?>

		<form class="request" method="POST">

			<p class="text">
				Se <strong>non hai una partita IVA e risiedi in Italia</strong> puoi usare il bottone qui sotto per richiedere il pagamento di <strong><?= $oldSeller->toPayString ?></strong><br><br>
				Se invece possiedi una partita IVA oppure non risiedi in Italia, ti preghiamo di contattarci all'indirizzo <a class="txt-sbold txt-c2" href="<?= sys_config('contact-email-link') ?>"><?= sys_config('contact-email') ?></a> per richiedere il pagamento.
			</p>

			<div class="flex justify-center mar-t-2">
				<button type="submit" class="btn button--auto-width" name="request-payment">
					<?= embed_icon('dollar') ?>
					Richiedi il pagamento
				</button>
			</div>

		</form>

	<?php } else { ?>

		<div class="request">

			<p class="text">
				I pagamenti dei guadagni accumulati non sono inviati automaticamente, ma devono essere richiesti cliccando qui sotto. È possibile richiedere il pagamento se il totale a pagare è superiore a <strong>25,00€ netti</strong> (31,25€ lordi).
			</p>

			<button type="button" class="btn button--auto-width place-center mar-t-1" disabled>
				<?= embed_icon('dollar') ?>
				Richiedi il pagamento
			</button>

		</div>

	<?php } ?>

	<form method="GET">

		<section id="recap">

			<!-- Sorting method -->
			<div id="recap-sort">

				<input type="hidden" name="id" value="<?= $oldSeller->id ?>">
				<input type="hidden" id="sort-inp" name="sort" value="<?= $sort ?>">
				<input type="hidden" id="year-inp" name="year" value="<?= $year ?>">

				<h1 class="sort-txt">Visualizza:</h1>

				<p class="sort-link clickable <?= $sort == 'designs' ? 'active' : '' ?>" data-sort="designs" onclick="$('#sort-inp').val($(this).data('sort')); $('#search-btn').click();">
					Grafiche
				</p>

				<p class="sort-link clickable <?= $sort == 'time' ? 'active' : '' ?>" data-sort="time" onclick="$('#sort-inp').val($(this).data('sort')); $('#search-btn').click();">
					Cronologia
				</p>

				<button type="submit" id="search-btn" class="hidden"></button>

			</div>

			<!-- Recap -->
			<div id="recap-data" class="grid gap1">
				<div class="rec_row">
					<div class="rec_cell">
						<p class="rec_lbl">Guadagno:</p>
						<input type="text" class="input wid-100" value="<?= format_price($seller->earned()) ?>" disabled>
					</div>

					<div class="rec_cell">
						<p class="rec_lbl inlb">Bonus: </p> <?= info_box("Per ogni capo venduto riceverai un ulteriore <strong>10%</strong> del guadagno come bonus. Puoi usare questo bonus per pagare le spese di spedizione quando acquisti sul nostro sito, oppure per comprare prodotti del tuo negozio senza pagarli.") ?>
						<input type="text" class="input wid-100" value="<?= format_price($seller->credit()) ?>" disabled>
					</div>
				</div>
				<div class="rec_row">
					<div class="rec_cell">
						<p class="rec_lbl">PAGATO:</p>
						<input type="text" class="input wid-100" value="<?= $oldSeller->payedString ?>" disabled>
					</div>

					<div class="rec_cell">
						<p class="rec_lbl">ALTRO:</p>
						<input type="text" class="input wid-100" value="<?= $oldSeller->otherTransfersString ?>" disabled>
					</div>

					<div class="rec_cell">
						<p class="rec_lbl">DA PAGARE:</p>
						<input type="text" class="input wid-100 txt-c1" style="font-weight: bold;" value="<?= $oldSeller->toPayString ?>" disabled>
					</div>
				</div>
			</div>

		</section>
	</form>

</div>

<div id="body" class="mar-t-3">

	<?php if ($saleN) { ?>

		<div class="list-cont ">
			<?php
			if ($sort == 'designs') include_once('list/sales_designs.php');
			else if ($sort == 'time') include_once('list/sales_history.php');
			?>
		</div>

	<?php } else { ?>

		<p class="pad-t-3 txt-cen">Ancora non hai realizzato nessuna vendita</p>

	<?php } ?>

</div>

{% end content %}