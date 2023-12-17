{% block main %}

<div id="content">

	<!-- Welcome panel -->
	<section id="welcome-panel" class="grid va-cen">

		<div class="bgr" <?= background_image('bgr.jpg') ?>></div>

		<div class="body">

			<h1 class="title txt-f txt-light">Registrati su<br><span class="txt-med">Shipping Dog</span></h1>

			<p class="txt-wht">
				Registrandoti sulla nostra piattaforma potrai monitorare lo stato dei tuoi ordini, seguire gli artisti che ti piacciono, aggiungere articoli ai tuoi preferiti e molto altro. Sei un disegnatore? Qui potrai vendere le tue grafiche stampate su tantissimi capi e accessori.
			</p>

		</div>

	</section>

	<!-- Form -->
	<form method="POST" autocomplete="off" id="form" class="form">

		<div class="head">
			<h2 class="title">Registrati</h2>
		</div>

		<div class="body">
			<?= generate_form($inputs, false, $error) ?>
		</div>

		<div class="footer">

			<button type="submit" name="submit" class="pbtn">Registrati</button>

			<div id="facebook" class="hidden">
				<button type="button" class="pbtn bgr-c3-full" onClick="fb_login();">
					<span class="soc-icon fb-ico wht"></span>
					<span class="align">Facebook Login</span>
				</button>
			</div>

			<a href="<?= client_url('/login') ?>" class="sbtn">Indietro</a>

		</div>

	</form>

</div>

{% end main %}