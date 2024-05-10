{% block form-body %}

<?php if ($done) { ?>

	<p class="confirmation text-center mar-t-1">E-email inviata</p>

	<p class="mar-t-1">Abbiamo inviato una e-email con i dati di accesso a: <span class="font-semibold"><?= $_POST['email'] ?></span>.</p>

<?php } else { ?>

	<p class="pad-t-1">
		Se hai dimenticato la tua password o il tuo username, basta inserire qui sotto l'indirizzo e-email che hai usato quando ti sei registrato e procederemo subito ad inviarti una e-email per recuperare i tuoi dati di accesso.
	</p>

	<input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" placeholder="Indirizzo e-email" class="mar-t-2">

	<?php if ($error == 'no-match') { ?>
		<p class="error">Non abbiamo trovato nessun account associato a questo indirizzo email</p>
	<?php } ?>

<?php } ?>

{% end form-body %}

<?php if (!$done) { ?>

	{% block form-footer %}

	<button type="submit" name="submit" class="btn">Recupera i dati di accesso</button>

	{% end form-footer %}

<?php } ?>