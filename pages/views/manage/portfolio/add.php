{% block content %}

<div id="pan" class="<?= $error ? 'error' : '' ?>" data-stage="<?= $error ? 2 : 1 ?>">

	<!-- Step 1: choose an image -->
	<section id="setup-sec" class="txt-cen pad-v-5 pad-h-2">

		<div id="upload-box" class="center-items clickable inlb mar-v-2 mar-h-auto">

			<div class="grid-flow center-content">
				<div id="upload-icon" class="icon-box img">
					<?= icon_embed('upload') ?>
				</div>

				<h1 id="upload-title" class="txt-bold txt-2rem pad-h-2">Carica un nuovo lavoro</h1>
			</div>

		</div>

		<div id="setup-data" class="mwid-650 mar-h-auto">
			<p class="error mar-b-2">Il file deve essere in <span class="txt-bold">PNG</span>. Il formato <span class="txt-bold wrong-extension"></span> non è accettato.</p>
			<h1 id="setup-stitle" class="txt-bold txt-15rem mar-b-1">Requisiti del file</h1>
			<p id="setup-txt" class="mar-h-auto">Il file deve essere un <strong>PNG</strong> con sfondo trasparente dove non vuoi che stampiamo. Raccomandiamo che il lato minore sia di almeno <strong>1000px</strong>.</p>
		</div>

	</section>

	<!-- Step 2: fill in the data -->
	<div id="data-form">

		<h1 class="form-title">Carica un lavoro</h1>

		<form class="form file-form" method="POST" enctype="multipart/form-data">

			<div class="grid gap1 pad-t-2">

				<div id="preview-box" class="txt-cen mar-b-2 <?php if ($error) echo 'hidden' ?>">
					<div class="inlb">
						<img id="preview" class="inlb" src="#" alt="">

						<div id="prev-over" class="bgr clickable">
							<div id="prev-icon" class="place-center" <?= icon_css('actions/upload_wht') ?>></div>
						</div>
					</div>
				</div>

				<?php generate_form($inputs, false, $error) ?>

				<div class="form-foot">

					<button type="submit" name="submit" class="pbtn txt-sbold file-submit">
						<span class="btn-icon bgr-f" <?= icon_svg('upload') ?>></span>
						<span class="btn-txt">Carica lavoro</span>
					</button>

					<div class="file-load"></div>

				</div>

			</div>
		</form>
	</div>
</div>

{% end content %}