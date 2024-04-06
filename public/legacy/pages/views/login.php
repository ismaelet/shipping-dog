{% block main %}

<div id="content">

	<!-- Welcome panel -->
	<section id="welcome-panel" class="grid va-cen">

		<div class="bgr" <?= background_image('bgr.jpg') ?>></div>

		<div class="body">

			<h1 class="title txt-f txt-light">Benvenuto su<br><span class="txt-med">Shipping Dog</span></h1>

			<p class="txt-wht">
				Entrando nella nostra piattaforma potrai monitorare lo stato dei tuoi ordini, seguire gli artisti che ti piacciono, aggiungere articoli ai tuoi preferiti e molto altro. Sei un disegnatore? Qui potrai vendere le tue grafiche stampate su tantissimi capi e accessori.
			</p>

		</div>

	</section>

	<!-- Form -->
	<form method="POST" id="form" class="form">

		<div class="head">
			<h2 class="title">Login</h2>
		</div>

		<div class="body">

			<input type="hidden" name="url" value="<?= $_GET['url'] ?? '' ?>">

			<input type="text" name="username" id="username" class="inp clear-error" placeholder="Username o E-mail" required>

			<input type="password" name="password" id="password" class="inp clear-error" placeholder="Password" required>

			<?php if ($error) { ?>
				<p class="error ">Dati di accesso errati</p>
			<?php } ?>

			<div class="options-bar va-cen">
				<label class="checkbox">
					<input type="checkbox" name="remember" checked>
					<span></span>

					<p>Resta connesso</p>
				</label>

				<a href="<?= client_url('/recover') ?>" class="txt-rig">
					<p class="">Hai dimenticato la password?</p>
				</a>

			</div>

		</div>

		<div class="footer">

			<div class="">
				<button type="submit" name="submit" id="submit" class="button">Log In</button>
			</div>

			<!-- <div class="">
				<button type="button" id="fb-btn" name="fb-btn" class="pbtn bgr-c3 bgr-c3-h">
					<input type="hidden" id="fbID" name="fbID" value="0">
					<span class="soc-icon fb-icon wht"></span>
					<span style="vertical-align: middle;">Facebook Login</span>
				</button>
			</div> -->

			<div class=" ld-del1">
				<a href="<?= client_url('/register') ?>" class="button button--secondary">Registrati</a>
			</div>

		</div>

	</form>

</div>

{% end main %}