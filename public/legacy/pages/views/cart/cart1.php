<?php

use ViewManager\Ecommerce\CartFlowBar;

?>

{% block main %}

<div id="slider">
	<div id="bgr" class="bgr"></div>
	<h1 id="slider-title">CA<span class="txt-c1">RR</span>ELLO</h1>
</div>

<div class="main-wrap">

	<?= CartFlowBar::render() ?>

	<div id="body" class="pad-t-4">

		<!-- Guest -->
		<form id="guest" class="section" method="POST">
			<h3 class="title">ORDINA SENZA REGISTRARTI</h3>
			<p class="txt">Clicca qui se vuoi procedere all'acquisto senza registrarti</p><br>
			<button type="submit" name="guest" class="button button--full-width">Ordina come ospite</button>
		</form>

		<!-- Login -->
		<section id="login" class="section">
			<h3 class="title">HAI GIÀ UN ACCOUNT?</h3>

			<form method="POST">

				<div class="grid gap1">
					<input type="hidden" name="url" value="<?= $_SERVER['REQUEST_URI']; ?>">
					<input type="text" name="username" class="inp" placeholder="Username">
					<input type="password" name="password" class="inp" placeholder="Password">
				</div>

				<?php if ($error == 'user_bad') { ?>

					<p class="error">Nome utente o password errati</p>

				<?php } ?>

				<button type="submit" name="submit" class="button mar-t-2 button--full-width">Log In</button><br>

				<div class="txt-rig mar-t-05">
					<p class="inlb"><a href="<?= client_url('/login/recover') ?>">Hai dimenticato la password?</a></p>
				</div>
			</form>

		</section>

		<!-- Register -->
		<div id="register" class="section">
			<h3 class="title">REGISTRATI</h3>
			<p class="txt">Vai qui sotto se vuoi registrarti</p><br>
			<a href="<?= CLIENT_ROOT; ?>/register"><button type="button" class="button button--secondary button--full-width">Registrati</button></a>
		</div>

		<!-- Continue -->
		<div id="continue" class="section">
			<h3 class="title">Continua con gli acquisti</h3>
			<p class="txt">Vuoi aggiungere altri articoli al carrello prima di continuare?</p><br>
			<a href="<?= CLIENT_ROOT; ?>/shop"><button type="button" class="button button--secondary button--full-width">Torna al negozio</button></a>
		</div>

	</div>
</div>

{% end main %}