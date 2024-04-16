<?php if ($status <> 1) { ?>

	{% block form-body %}

	<p>
		I dati di spedizione sono già stati trasferiti al corriere, quindi non è più possibile modificare le informazioni inserite. Per aggiornare l'indirizzo di spedizione contatta direttamente il <a class="txt-c1-h" href="https://gls-group.com/IT/it/servizi-online/contatti"><strong>corriere GLS</strong></a> sul loro sito:<br><br>

		<a class="txt-c2 txt-med" href="https://gls-group.com/IT/it/servizi-online/contatti">https://gls-group.com/IT/it/servizi-online/contatti</a><br><br>

		Oppure contattaci all'indirizzo <a class="txt-c2 txt-med" href="<?= sys_config('contact-email-link') ?>"><?= sys_config('contact-email') ?></a> e cercheremo di aiutarti.
	</p>

	{% end form-body %}

	{% block form-footer %}

	<a href="<?= client_url('/contacts') ?>" class="btn">I nostri contatti</a>

	<?php if ($address['user_id'] == auth()->id()) { ?>
		<a class="btn btn--secondary" href="<?= client_url('/order?id=' . $id) ?>">Indietro</a>
	<?php } ?>

	{% end form-footer %}

<?php } else { ?>

	{% block form-body %}

	<?= generate_form($inputs, false, false, $done); ?>

	{% end form-body %}

	{% block form-footer %}

	<button type="submit" name="submit" id="submit" class="btn">Cambia Indirizzo</button>

	<?php if ($address['user_id'] == auth()->id()) { ?>
		<a class="btn btn--secondary" href="<?= client_url('/order?id=' . $id) ?>">Indietro</a>
	<?php } ?>

	{% end form-footer %}

<?php } ?>