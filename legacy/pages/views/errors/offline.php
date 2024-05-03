{% block main %}

<!-- Body -->
<div class="page-content pad-h-2 txt-cen">
	<!-- <img class="logo" src="<?= client_path(LOGO_DIR . 'logo.svg') ?>" alt=""> -->
	<h1 class="font-semibold txt-upp"><?= ('Sito Offline') ?></h1>
	<p class="mwid-600 txt-12rem">
		<?= ("Il nostro sito è al momento in offline per un aggiornamento. Torneremo attivi non appena possibile.") ?>
	</p>
</div>

<!-- Login button -->
<form class="login flex ha-cen pad-b-1" method="POST">
	<button type="submit" class="login-btn btn-with-icons clickable" name="submit">
		<span class="text-icon small bgr-c1" <?= embed_icon('key') ?>></span>
		<span class="login-text br-b-1 br-c1 txt-c1 txt-09rem"><?= ('Entra come amministratore') ?></span>
	</button>
</form>

{% end main %}