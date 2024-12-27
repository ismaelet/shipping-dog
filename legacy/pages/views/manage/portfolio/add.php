{% block content %}

<div id="pan" class="<?= $error ? 'error' : '' ?>" data-stage="<?= $error ? 2 : 1 ?>">

	<!-- Step 1: choose an image -->
	<section id="setup-sec" class="text-center pad-v-5 pad-h-2">

		<div id="upload-box" class="center-items clickable inlb mar-v-2 mar-h-auto">

			<div class="grid-flow center-content">
				<div id="upload-icon" class="icon-box img">
					<?= embed_icon('upload') ?>
				</div>

				<h1 id="upload-title" class="font-semibold txt-2rem pad-h-2">Carica una nuova grafica</h1>
			</div>

		</div>

		<div id="setup-data" class="mwid-650 mar-h-auto">
			<p class="error mar-b-2">Il file deve essere in <span class="font-semibold">PNG</span>. Il formato <span class="font-semibold wrong-extension"></span> non è accettato.</p>
			<h1 id="setup-stitle" class="font-semibold txt-15rem mar-b-1">Requisiti del file</h1>
			<p id="setup-txt" class="mar-h-auto">Il file deve essere un <strong>PNG</strong> con sfondo trasparente dove non vuoi che stampiamo. Raccomandiamo che il lato minore sia di almeno <strong>1000px</strong>.<br><br>
				Ricordiamo inoltre bordi con ombreggiature e sfumature di ogni tipo hanno una resa ottimale <strong>SOLO</strong> su capi bianchi, quindi consigliamo grafiche dai <strong>bordi netti</strong> per risaltare al meglio sui capi scuri.</p>
		</div>

	</section>

	<!-- Step 2: fill in the data -->
	<div id="data-form">

		<h1 class="form-title">Carica una grafica</h1>

		<form action="<?= route('designs.store') ?>" class="form file-form" method="POST" enctype="multipart/form-data">

			<div class="grid gap1 pad-t-2">

				<div id="preview-box" class="text-center mar-b-2 <?php if ($error) echo 'hidden' ?>">
					<div class="inlb">
						<img id="preview" class="inlb" src="#" alt="">

						<div id="prev-over" class="bgr clickable">
							<div id="prev-icon" class="place-center" <?= icon_css('actions/upload_wht') ?>></div>
						</div>
					</div>
				</div>

				<?php generate_form($inputs, false, $error) ?>

				<div class="form-foot flex justify-center">

					<button type="submit" name="submit" class="btn btn-auto-width txt-sbold file-submit">
						<?= embed_icon('upload') ?>
						Carica la grafica
					</button>

					<div class="file-load"></div>

				</div>

			</div>
		</form>
	</div>
</div>

{% end content %}