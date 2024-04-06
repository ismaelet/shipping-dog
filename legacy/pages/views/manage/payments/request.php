<?php if ($edit || !$data || !$data['nationalId']) { ?>

	{% block form-body %}

	<p>
		Invieremo il pagamento al tuo account <strong>PayPal</strong>. Se non possiedi un account PayPal puoi crearlo <a href="https://www.paypal.com/it/webapps/mpp/account-selection" class="txt-c2 txt-sbold">cliccando qui</a>, è rapido e completamente gratuito.<br><br>
		Per poter generare i documenti necessari per il pagamento, abbiamo bisogno di alcuni tuoi dati:
	</p>

	<?= generate_form($inputs, false, false, $done) ?>

	{% end form-body %}

	{% block form-footer %}

	<button type="submit" class="button" name="submit"><?= $data ? 'Modifica' : 'Invia' ?></button>

	<?php if ($edit) { ?>

		<a class="button button--secondary" href="?edit=0">Indietro</a>

	<?php } ?>

	{% end form-footer %}

<?php } else { ?>

	{% block form-body %}

	<div class="grid gap2">

		<p class="txt-1rem">
			Per poter procedere al pagamento ti chiediamo di stampare il <strong>PDF</strong> che puoi scaricare qui sotto, firmarlo ed inviarlo all'indirizzo e-email <a href="<?= sys_config('contact-email-link') ?>"><strong><?= sys_config('contact-email') ?></strong></a>.
		</p>

		<p class="txt-1rem">
			<?php if ($seller->toPay >= 70) { ?>
				Il campo della marca da bollo <span class="txt-bold txt-red">è da lasciare vuoto</span>.
			<?php } ?>
		</p>

		<p class="txt-1rem">
			Procederemo al pagamento entro <strong><?= $waitingDays > 1 ? $waitingDays . ' giorni lavorativi' : 'un giorno lavorativo' ?></strong> dal momento in cui riceveremo il documento firmato.<br>
			<?= $waitingDays > 2 ? "Ci scusiamo per il tempo di attesa elevato, putroppo dipende dalle nuove normative burocratiche introdotte nel 2022." : '' ?>
		</p>

	</div>

	{% end form-body %}

	{% block form-footer %}

	<button type="submit" class="button" name="download">
		<?= embed_icon('download') ?>
		Scarica il <strong>PDF</strong>
	</button>

	<a class="button button--secondary" href="?edit=1">Modifica i dati di fatturazione</a>

	{% end form-footer %}

<?php } ?>