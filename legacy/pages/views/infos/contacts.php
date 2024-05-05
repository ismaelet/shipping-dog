{% block main %}

<!-- Slider -->
<div id="slider" class="grid center-content">

	<div id="bgr" class="bgr"></div>

	<h1 id="title" class="txt-wht txt-cen">Conta<span class="txt-c1">tt</span>i</h1>

</div>

<!-- Body -->
<div id="body">

	<div class="main-wrap">

		<div id="grid" class="grid2 gap3 al-top">

			<!-- Contact data -->
			<section id="data" class="grid-cell">

				<h2 class="stitle mar-b-2">Shipping<br>Dog SNC</h2>
				<p class="data-txt">Andrea Trentini</p>
				<p class="data-txt"><a href="<?= sys_config('contact-email-link') ?>"><?= sys_config('contact-email') ?></a></p>
				<p class="data-txt">Piazza Scopoli, 9<br>38033 Cavalese (TN)</p>

				<section id="contacts" class="flex gap1 mar-t-1">
					<a href="<?= sys_config('contact-whatsapp-link') ?>" class="soc-icon block icon-box fill-c1-h">
						<?= embed_icon('contacts/whatsapp') ?>
					</a>
					<a href="<?= sys_config('contact-instagram-link') ?>" class="soc-icon block icon-box fill-c1-h">
						<?= embed_icon('contacts/instagram') ?>
					</a>
					<a href="<?= sys_config('contact-facebook') ?>" class="soc-icon block icon-box fill-c1-h">
						<?= embed_icon('contacts/facebook') ?>
					</a>
				</section>

				<div class="line mar-v-2"></div>

				<h3 class="txt-15rem mar-b-2 txt-med">Sito web realizzato da:</h3>
				<p class="data-txt">Ismaele Tuffanelli</p>
				<p class="data-txt"><a href="emailto:ismaele.tuffanelli@gmail.com">ismaele.tuffanelli@gmail.com</a></p>

				<div class="line mar-v-2"></div>

				<p class="data-txt">
					<a href="<?= client_url('/refund') ?>">Cambio taglia o reso</a>
				</p>

			</section>

			<!-- Contact form -->
			<section id="form" class="grid-cell">
				<form method="POST" autocomplete="off">

					<div id="form-anch" class="anchor"></div>

					<h2 class="stitle txt-cen">Scrivici</h2>

					<?php if ($sent) { ?>
						<p class="confirmation mar-t-2">Messaggio inviato, ti risponderemo al più presto</p>
					<?php } ?>

					<?php if ($error == 'email_bad') { ?>
						<p class="error">Errore: indirizzo email non riconosciuto. Puoi comunque a scriverci direttamente a <?= sys_config('contact-email-link') ?>, invece di usare questo modulo.</p>
					<?php } ?>

					<?php if ($error == 'word_bad') { ?>
						<p class="error">Errore: il messaggio non ha superato i nostri filtri anti-spam. Puoi comunque scriverci direttamente a <?= sys_config('contact-email-link') ?>, invece di usare questo modulo.</p>
					<?php } ?>

					<div class="form-lbl-box">
						<p class="form-lbl">Nome e cognome</p>
					</div>
					<input type="text" name="name" class="form-inp" onfocus="$(this).prev().addClass('focus')" onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}" required>

					<div class="form-lbl-box">
						<p class="form-lbl">Indirizzo email</p>
					</div>
					<input type="email" name="email" class="form-inp" onfocus="$(this).prev().addClass('focus')" onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}" required>

					<div class="form-lbl-box">
						<p class="form-lbl">Testo del messaggio</p>
					</div>
					<textarea name="text" class="form-textarea" maxlength="500" onfocus="$(this).prev().addClass('focus')" onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}" required></textarea>

					<div class="txt-rig mar-t-2">
						<button type="submit" id="submit" name="submit" class="btn">Invia</button>
					</div>
				</form>
			</section>

		</div>
	</div>
</div>

{% end main %}