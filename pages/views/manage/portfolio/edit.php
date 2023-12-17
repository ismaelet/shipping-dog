{% block main %}

<div id="body" class="main-wrap pad-t-4 pad-b-3" data-hroot="<?= CLIENT_ROOT ?>" data-design-id="<?= $design->id ?>">

	<!-- Name -->
	<form method="POST">
		<div id="name-box" class="grid-flow stack-content center-content">
			<input type="text" class="js-change-name" id="name" name="name" maxlength="25" data-id="<?= $id ?>" value="<?= $design->name ?>">

			<?= icon_embed('correct', ['name-change-confirmation', 'js-change-name-confirmation']) ?>
		</div>
	</form>

	<!-- General data -->
	<div id="des-grid" class="grid mar-t-3 mar-b-5">

		<!-- Design images -->
		<section id="des-sec">

			<h3 class="sec-title">Grafica</h3>

			<!-- Light version -->
			<form method="POST" class="mar-b-2 file-form" enctype="multipart/form-data">

				<div id="des-light-anch" class="anchor"></div>

				<p class="data-title inlb"><?= $custom ? 'Immagine di anteprima' : 'Su capi chiari' ?></p><?php

																											use Ecommerce\Product\Color;

																											if ($custom) info_box("Questa immagine è usata solo nelle sezioni dove compare la grafica senza un mockup. Essendo questo un prodotto particolare i mockup non vengono creati automaticamente.") ?>

				<div id="des-light" class="des-box txt-0 pos-rel" onclick="$(this).parent().find('.file-inp').click();">

					<img class="design block" style="background-color: <?= $design->color ?>;" src="<?= client_path('/uploads/designs/' . $id . '.png') ?>">

					<div class="des-over clickable file-stick">

						<div class="des-icon img cont icon cen file-cancel bgr-f" <?= icon_svg('upload') ?>></div>

						<div class="file-load wid-100 cen"></div>

					</div>

				</div>

				<input type="file" name="file" class="file-inp hidden">
				<button type="submit" name="upload-light" class="file-submit hidden"></button>

				<?php if ($error == 'light_file_size') { ?><p class="error">L'immagine selezionata è troppo grande</p><?php } ?>
				<?php if ($error == 'light_file_type') { ?><p class="error">L'immagine deve essere un PNG</p><?php } ?>
				<?php if ($error == 'light_file_reso') { ?><p class="error">l'immagine deve avere meno di 10.000px di lato</p><?php } ?>
				<?php if ($error == 'light_error') { ?><p class="error">Errore nel caricamento</p><?php } ?>

				<button type="button" class="mob768 button button--secondary button--full-width mar-t-2 file-cancel" onclick="$(this).parent().find('.file-inp').click();">
					<span class="btn-icon bgr-c900" <?= icon_svg('upload') ?>></span>
					<span class="txt"><strong>Ricarica grafica</strong></span>
				</button>

			</form>

			<!-- Dark version -->
			<?php if (!$custom) { ?>

				<form method="POST" class="file-form" enctype="multipart/form-data">

					<div id="des-dark-anch" class="anchor"></div>

					<?php $dark = file_exists(SERVER_ROOT . '/uploads/designs/' . $id . '_dark.png') ?>

					<p class="data-title inlb al-cen">Su capi scuri<?= $dark ? '' : ' (opzionale)' ?></p><?= info_box("Se la tua grafica non è molto visibile sui capi neri, ma ne hai una versione alternativa più chiara, che va bene per i prodotti scuri, puoi caricare qui questa versione, così da non doverle inserire come due grafiche separate.") ?>

					<!-- If dark version exists -->
					<?php if (file_exists(SERVER_ROOT . '/uploads/designs/' . $id . '_dark.png')) { ?>

						<div class="des-box txt-0 pos-rel" onclick="$(this).parent().find('.file-inp').click();">

							<img src="<?= client_path('/uploads/designs/' . $id . '_dark.png') ?>" class="design bgr-blk block">

							<div class="des-over clickable file-stick">

								<div class="des-icon img icon cont cen file-cancel bgr-f" <?= icon_svg('upload') ?>></div>

								<div class="file-load wid-100 cen"></div>

							</div>

						</div>

						<?php if ($error == 'dark_file_size') { ?><p class="error">L'immagine selezionata è troppo grande</p><?php } ?>
						<?php if ($error == 'dark_file_type') { ?><p class="error">L'immagine deve essere un PNG</p><?php } ?>
						<?php if ($error == 'dark_file_reso') { ?><p class="error">l'immagine deve avere meno di 10.000px di lato</p><?php } ?>
						<?php if ($error == 'dark_error') { ?><p class="error">Errore nel caricamento</p><?php } ?>

						<button type="button" class="mob768 button button--secondary button--full-width mar-t-2 file-cancel" onclick="$(this).parent().find('.file-inp').click();">
							<span class="btn-icon" <?= icon_css('actions/upload_blk') ?>></span>
							<span class="txt"><strong>Ricarica grafica</strong></span>
						</button>

						<!-- If dark version doesn't exists -->
					<?php } else { ?>

						<div id="dark-empty" class="center-content clickable" onclick="$(this).parent().find('.file-inp').click();">

							<div class="txt-cen file-cancel">
								<div class="des-icon icon img cont inlb" <?= icon_svg('upload') ?>></div>
								<p class="txt-bold mar-t-05">Carica una versione diversa per i capi scuri</p>
							</div>

							<div class="file-load wid-100 cen"></div>

						</div>

					<?php } ?>

					<input type="file" name="file" class="file-inp hidden">
					<button type="submit" name="upload-dark" class="file-submit hidden"></button>

				</form>


				<form method="POST" class="file-form mar-t-2" enctype="multipart/form-data">

					<?php $vector = file_exists(SERVER_ROOT . '/uploads/designs_vector/' . $id . '.pdf') || file_exists(SERVER_ROOT . '/uploads/designs_vector/' . $id . '.ai') ?>

					<div id="des-vector-anch" class="anchor"></div>

					<div class="">

						<?php if ($vector) { ?>

							<p class="data-title">Ricarica il file vettoriale</p>

							<p class="pad-v-1">Hai già caricato il file vettoriale, ma se per qualche motivo vuoi sostituirlo, puoi ricaricarlo usando il bottone qui sotto.</p>

						<?php } else { ?>

							<p class="data-title inlb al-cen">File vettoriale (opzionale)</p>

							<?= $vector ? '' : info_box("Oltre al file in PNG che hai già caricato, consigliamo di caricare anche il design in formato <span class='txt-bold'>PDF</span> o <span class='txt-bold'>AI</span> per la massima qualità di stampa possibile. Se non hai il design in questo formato non preoccuparti, riusciremo comunque a realizare i tuoi prodotti con stampe di alta qualità.")
							?>

						<?php } ?>

					</div>

					<input type="file" name="file" class="file-inp inp clickable">
					<button type="submit" name="upload-vector" class="file-submit hidden"></button>
					<div class="file-load"></div>

				</form>

			<?php } ?>

		</section>

		<!-- Design data -->
		<form id="data-sec" method="POST">

			<section id="tags-sec" class="mar-b-2">

				<div id="tags-anch" class="anchor"></div>

				<h3 class="sec-title">Tags</h3>

				<?php if ($done == 'tags') { ?><p class="confirmation">Tag modificati con successo</p><?php } ?>

				<div class="">
					<textarea name="tags" class="textarea mar0" placeholder="(Opzionale) Puoi inserire fino a 10 tag separati da virgola. I tag rendono il tuo design più facile da trovare."><?= $design->tags ? implode(',', $design->tags) : '' ?></textarea>
				</div>

				<button type="submit" class="button button--full-width mar-t-1" name="tags-submit">Aggiorna</button>

			</section>

			<?php if (!$design->custom) { ?>

				<section id="color-sec">

					<div id="colors-anch" class="anchor"></div>

					<h3 class="sec-title">Colori</h3>

					<?php if ($done == 'colors') { ?><p class="confirmation">Colori modificati con successo</p><?php } ?>

					<p class="mar-b-1 mar-t-05">
						Quando applichi le modifiche qui, tutte le impostazioni riguardanti i colori di ogni articolo con questa grafica verranno <strong>sovrascritte</strong>. Per cambiare i colori solo di un articolo specifico puoi usare la lista dei capi qui in basso.
					</p>

					<div class="colors-pan">

						<!-- Background color -->
						<p class="data-title inlb">Colore di sfondo</p><?= info_box("Questo colore verrà usato come sfondo della tua grafica in alcune sezioni del sito. Scegli un colore che faccia risaltare bene la tua immagine. Non inflenza in alcun modo la stampa o il colore dei capi su cui la grafica sarà acquistabile.") ?>

						<div class="field">

							<div class="inp-box pos-rel">

								<div id="color-bgr" class="inlb va-cen" style="background-color: <?= $design->color ?>">
									<input type="color" name="color-hex" id="color-bgr-inp" value="<?= $design->color ?>">
								</div>

								<p id="color-bgr-title" class="inlb va-cen txt-sbold txt-c-title mar-l-1">HEX: <span id="color-bgr-hex" class="txt-upp"><?= $design->color ?></span></p>

								<div id="color-bgr-box" class="bgr clickable"></div>

							</div>

						</div>

						<!-- Default color -->
						<p class="data-title inlb">Colore predefinito</p><?= info_box("Qui puoi selezionare il colore del capo che sarà mostrato come prima scelta in tutte le anteprime. Non influenza gli accessori con un solo colore disponibile, come astucci e sacche.") ?>

						<div class="field colorsD">

							<div class="inp-box color-grid">

								<?php
								$defaultColorId = DB::find('colorID FROM items WHERE desID=? AND typeID=1', [$design->id]);
								while ($row = $res_colorD->fetch_assoc()) {
									$color = new Color($row['ID']);
								?>

									<label class="inlb color-box active">

										<input type="checkbox" class="cbx hidden colorD-<?= $color->slug ?>" name="colorD[]" value="<?= $color->id ?>" <?= $color->id == $defaultColorId ? 'checked' : '' ?>>

										<div class="color-icon" for="colorD-<?= $color->slug ?>" style="background-color: #<?= $color->hex ?>;"></div>

									</label>

								<?php } ?>

							</div>

						</div>

						<button type="submit" name="colors-submit" class="button button--full-width">Applica a <span class="fw-bold">TUTTI</span> i capi</button>

					</div>
				</section>

			<?php } ?>

		</form>
	</div>

	<!-- Items -->
	<section id="items-sec" class="mar-t-2">

		<h2 class="txt-cen txt-med">Prodotti collegati</h2>
		<p class="txt-cen mar-b-2 mar-t-2">Qui puoi modificare il colore dei capi e la dimensione di stampa nei prodotti con questa grafica.</p>

		<div class="item-list grid4 grid3-1024w grid2-480w gap2 al-top pos-rel">
			<div id="items-load-bgr" class="bgr bgr-c-bgr">
				<div class="load-icon mar-t-4"></div>
			</div>

			<!-- AJAX -->
			<div class="async-items" data-design-id="<?= $design->id ?>"></div>

		</div>

		<form class="mar-t-4" method="POST">

			<button type="submit" name="delete" id="delete" class="button button--secondary button--bad button--auto-width place-center" onclick="return confirm('Sei sicuro di voler eliminare definitivamente la grafica e tutti i prodotti associati?')">
				<?= icon_embed('delete') ?>
				Elimina la grafica
			</button>

		</form>

	</section>
</div>

{% end main %}