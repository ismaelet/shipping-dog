<?php

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductMockup;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Product\ProductTypeMockup;
use Legacy\Classes\Ecommerce\Product\Color as LegacyColor;

?>

{% block main %}

<div id="body" class="main-wrap pad-t-4 pad-b-3" data-design-id="<?= $design->id ?>">

	<!-- Name -->
	<form method="POST">
		<div id="name-box" class="grid-flow stack-content center-content">
			<input type="text" class="js-change-name" id="name" name="name" maxlength="25" data-id="<?= $id ?>" value="<?= $design->name ?>">

			<?= embed_icon('correct', ['name-change-confirmation', 'js-change-name-confirmation']) ?>
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

				<p class="data-title inlb"><?= $custom ? 'Immagine di anteprima' : 'Su capi chiari' ?></p>

				<?php

				if ($custom) info_box("Questa immagine è usata solo nelle sezioni dove compare la grafica senza un mockup. Essendo questo un prodotto particolare i mockup non vengono creati automaticamente.") ?>

				<div id="des-light" class="des-box relative" onclick="$(this).parent().find('.file-inp').click();">

					<img class="design block" style="background-color: <?= $design->color ?>;" src="<?= public_asset('designs/' . $id . '.png') ?>">

					<div class="des-over clickable file-stick text-white">

						<div class="des-icon img cont icon cen file-cancel"><?= embed_icon('upload') ?></div>

						<div class="file-load wid-100 cen"></div>

					</div>

				</div>

				<input type="file" name="file" class="file-inp hidden">
				<button type="submit" name="upload-light" class="file-submit hidden"></button>

				<?php if ($error == 'light_file_size') { ?><p class="error">L'immagine selezionata è troppo grande</p><?php } ?>
				<?php if ($error == 'light_file_type') { ?><p class="error">L'immagine deve essere un PNG</p><?php } ?>
				<?php if ($error == 'light_file_reso') { ?><p class="error">l'immagine deve avere meno di 10.000px di lato</p><?php } ?>
				<?php if ($error == 'light_error') { ?><p class="error">Errore nel caricamento</p><?php } ?>

				<button type="button" class="mob768 button btn--secondary button--full-width mar-t-2 file-cancel" onclick="$(this).parent().find('.file-inp').click();">
					<?= embed_icon('upload') ?>
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

						<div class="des-box txt-0 relative" onclick="$(this).parent().find('.file-inp').click();">

							<img src="<?= public_asset('designs/' . $id . '_dark.png') ?>" class="design bgr-blk block">

							<div class="des-over clickable file-stick">

								<div class="des-icon img icon cont cen file-cancel"><?= embed_icon('upload') ?></div>

								<div class="file-load wid-100 cen"></div>

							</div>

						</div>

						<?php if ($error == 'dark_file_size') { ?><p class="error">L'immagine selezionata è troppo grande</p><?php } ?>
						<?php if ($error == 'dark_file_type') { ?><p class="error">L'immagine deve essere un PNG</p><?php } ?>
						<?php if ($error == 'dark_file_reso') { ?><p class="error">l'immagine deve avere meno di 10.000px di lato</p><?php } ?>
						<?php if ($error == 'dark_error') { ?><p class="error">Errore nel caricamento</p><?php } ?>

						<button type="button" class="mob768 button btn--secondary button--full-width mar-t-2 file-cancel" onclick="$(this).parent().find('.file-inp').click();">
							<span class="btn-icon" <?= icon_css('actions/upload_blk') ?>></span>
							<span class="txt"><strong>Ricarica grafica</strong></span>
						</button>

						<!-- If dark version doesn't exists -->
					<?php } else { ?>

						<div id="dark-empty" class="center-content clickable" onclick="$(this).parent().find('.file-inp').click();">

							<div class="text-center file-cancel">
								<div class="des-icon icon img cont inlb text-white"><?= embed_icon('upload') ?></div>
								<p class="font-semibold mt-2">Carica una versione diversa per i capi scuri</p>
							</div>

							<div class="file-load wid-100 cen"></div>

						</div>

					<?php } ?>

					<input type="file" name="file" class="input file-inp hidden">
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

							<?= $vector ? '' : info_box("Oltre al file in PNG che hai già caricato, consigliamo di caricare anche il design in formato <span class='font-semibold'>PDF</span> o <span class='font-semibold'>AI</span> per la massima qualità di stampa possibile. Se non hai il design in questo formato non preoccuparti, riusciremo comunque a realizare i tuoi prodotti con stampe di alta qualità.")
							?>

						<?php } ?>

					</div>

					<input type="file" name="file" class="file-inp input clickable">
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
					<textarea name="tags" class="textarea mar0" placeholder="(Opzionale) Puoi inserire fino a 10 tag separati da virgola. I tag rendono il tuo design più facile da trovare."><?= $design->tags?->tags ?></textarea>
				</div>

				<button type="submit" class="btn button--full-width mar-t-1" name="tags-submit">Aggiorna</button>

			</section>

			<?php if (!$design->custom) { ?>

				<section id="color-sec">

					<div id="colors-anch" class="anchor"></div>

					<h3 class="sec-title">Colori</h3>
					<?php if ($done == 'colors') { ?><p class="confirmation">Colori modificati con successo</p><?php } ?>

					<p class="mar-b-1 mt-2">
						Quando applichi le modifiche qui, tutte le impostazioni riguardanti i colori di ogni articolo con questa grafica verranno <strong>sovrascritte</strong>. Per cambiare i colori solo di un articolo specifico puoi usare la lista dei capi qui in basso.
					</p>

					<div class="colors-pan">

						<!-- Background color -->
						<p class="data-title inlb">Colore di sfondo</p><?= info_box("Questo colore verrà usato come sfondo della tua grafica in alcune sezioni del sito. Scegli un colore che faccia risaltare bene la tua immagine. Non inflenza in alcun modo la stampa o il colore dei capi su cui la grafica sarà acquistabile.") ?>

						<div class="field">

							<div class="inp-box relative">

								<div id="color-bgr" class="inlb va-cen" style="background-color: <?= $design->color ?>">
									<input type="color" name="color-hex" id="color-bgr-inp" value="<?= $design->color ?>">
								</div>

								<p id="color-bgr-title" class="inlb va-cen txt-sbold txt-c-title mar-l-1">HEX: <span id="color-bgr-hex" class="uppercase"><?= $design->color ?></span></p>

								<div id="color-bgr-box" class="bgr clickable"></div>

							</div>

						</div>

						<!-- Default color -->
						<p class="data-title inlb">Colore predefinito</p><?= info_box("Qui puoi selezionare il colore del capo che sarà mostrato come prima scelta in tutte le anteprime. Non influenza gli accessori con un solo colore disponibile, come astucci e sacche.") ?>

						<div class="field colorsD">

							<div class="inp-box color-grid">

								<?php
								$defaultColorId = \Legacy\System\Classes\LegacyDB::find('color_id FROM products WHERE design_id = ? AND type_id=1', [$design->id]);
								while ($row = $res_colorD->fetch_assoc()) {
									$color = new LegacyColor($row['id']);
								?>

									<label class="inlb color-box active">

										<input type="checkbox" class="cbx hidden colorD-<?= $color->slug ?>" name="colorD[]" value="<?= $color->id ?>" <?= $color->id == $defaultColorId ? 'checked' : '' ?>>

										<div class="color-icon" for="colorD-<?= $color->slug ?>" style="background-color: #<?= $color->hex ?>;"></div>

									</label>

								<?php } ?>

							</div>

						</div>

						<button type="submit" name="colors-submit" class="btn button--full-width">Applica a <span class="fw-bold">TUTTI</span> i capi</button>

					</div>
				</section>

			<?php } ?>

		</form>
	</div>

	<!-- Items -->
	<section id="items-sec" class="mar-t-2">

		<h2 class="text-component-heading text-center">Prodotti collegati</h2>
		<p class="text-center mar-b-2 mar-t-2">Qui puoi modificare il colore dei capi e la dimensione di stampa nei prodotti con questa grafica.</p>

		<div class="item-list grid4 grid3-1024w grid2-480w gap2 al-top relative">
			<div id="items-load-bgr" class="bgr bgr-c-bgr">
				<div class="load-icon mar-t-4">
					<?= embed_icon('loading') ?>
				</div>
			</div>

			<div class="async-items" data-design-id="<?= $design->id ?>">
				<?php

				if (!$design->custom) {
					$types = ProductType::where('hidden', 0)->orderBy('category')->orderBy('id')->get();
				}

				foreach ($types as $type) {
					$product = Product::where('design_id', $design->id)->where('type_id', $type->id)->first();
					$gender = $type->gender;
					$active = $product->status;
					$productImage = $product->image();

					// Tabs
					$tabs = [];

					if (count($type->colors) > 1) {
						$tabs[] = ['Colori', 'colors'];
					}

					foreach ($type->positions as $position) {
						if ($position->editable) {
							$tabs[] = [$position->name, 'position', $position];
						}
					}

					use_natural_keys($tabs);

					$designLight = '/designs/' . $design->id . '.png';
					$designDark = file_exists(public_storage($file = '/designs/' . $design->id . '_dark.png')) ? $file : $designLight;
				?>

					<div id="item-<?= $type->id ?>" class="item <?= $active ? 'active' : '' ?>">

						<div class="item-img item-panel-open relative">
							<img src="<?= $productImage ?>" alt="" class="img-fill">
							<div class="item-over bgr"></div>
						</div>

						<h3 class="type-name  txt-med text-center mt-2">
							<?= $type->name ?>
						</h3>

						<form method="POST" class="img-buttons grid mt-2">

							<input type="hidden" name="type_id" value="<?= $type->id ?>">

							<?php if ($product->status) { ?>

								<button type="submit" name="off" class="btn off-btn switch-btn ">
									<?= embed_icon('add-bgr') ?>
									Attivo
								</button>

								<p class="sbtn edit-btn switch-btn item-panel-open">
									<?= embed_icon('settings') ?>
									Modifica
								</p>

							<?php } else { ?>

								<button type="submit" name="on" class="btn btn--secondary on-btn switch-btn">
									<?= embed_icon('add-bgr') ?>
									Disattivato
								</button>

							<?php } ?>

						</form>

						<div class="item-panel" data-id=<?= $productId ?>>

							<div class="box relative">

								<div class="tabs">

									<?php foreach ($tabs as $key => $tab) { ?>
										<h3 class="tab uppercase <?= $key == 1 ? 'active' : '' ?>" data-id="<?= $key ?>"><?= $tab[0] ?></h3>
									<?php } ?>

								</div>

								<div class="wrapper">

									<div class="close-icon item-panel-close icon clickable text-neutral-700"><?= embed_icon('close') ?></div>

									<div class="content">

										<!-- Position tabs -->
										<?php foreach ($tabs as $key => $tab) {
											if ($tab[1] == 'position') {
												$position = $tab[2];

												$enabled = in_array($position->id, $product->availablePositionIds);

												$productTypeMockup = ProductTypeMockup::firstByTypeId($product->type_id, $position->id);
												$productMockup = ProductMockup::where('product_id', $productId)
													->where('position_id', $position->id)
													->first();

												$pX = $productMockup->pos_x ?? $productTypeMockup->pos_x_def;
												$pY = $productMockup->pos_y ?? $productTypeMockup->pos_y_def;
												$sX = $sY = $productMockup->size ?? $productTypeMockup->siz_x_def;

												$sX_max = $productTypeMockup->siz_x_max;
												$sY_max = $productTypeMockup->siz_y_max;
												$pX_min = $productTypeMockup->pos_x_min;
												$pY_min = $productTypeMockup->pos_y_min;

												$orientation = $product->design->ratio > $sX_max / $sY_max;

												$clothing = $type->image($product->color_id, $position->imagePositionId);
										?>

												<section class="section<?= $key ?> section positions <?= $key == 1 ? 'active' : '' ?> <?= $enabled ? '' : 'disabled' ?>" data-enabled="<?= $enabled ? 1 : 0 ?>" data-image-position="<?= $position->imagePositionId ?>">

													<input type="hidden" class="js-enabled" data-position="<?= $position->id ?>" data-enabled="<?= $enabled ? 1 : 0 ?>">

													<!-- Preview image -->
													<div class="image-panel">

														<div class="image mar-h-auto relative">

															<?php if ($design->custom) { ?>

																<!-- If it's a custom static image -->
																<div class="bgr" <?= temp_bg($product->image) ?>></div>

															<?php } else { ?>

																<!-- If it's a normal image -->
																<div class="background bgr" <?= temp_bg('product_types/mockups/bgr/grey.png') ?>></div>

																<div class="clothing bgr" <?= temp_bg($clothing) ?> data-type-id="<?= $product->type_id ?>"></div>

																<div class="design-box" style="
											width: <?= $sX_max ?>%;
											height: <?= $sY_max ?>%;
											top: <?= $pY_min ?>%;
											left: <?= $pX_min ?>%;">

																	<div class="design light <?= $product->color->dark ? '' : 'active' ?>" style="
												background-image: url(<?= public_asset($designLight) ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>">
																	</div>

																	<div class="design dark <?= $product->color->dark ? 'active' : '' ?>" style="
												background-image: url(<?= public_asset($designDark) ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>">
																	</div>

																</div>

																<?php if (file_exists(public_storage($file = str_replace('.', '-det.', $clothing)))) { ?>
																	<div class="details bgr" <?= temp_bg($file) ?>></div>
															<?php }
															} ?>

														</div>

													</div>

													<form method="POST" class="data">

														<input type="hidden" name="product_id" value="<?= $productId ?>">
														<input type="hidden" name="position_id" value="<?= $position->id ?>">

														<div>
															<h1 class="txt-15rem sec-title font-semibold mar-b-1 brb2 br-c2">Stampa in posizione <?= strtolower($position->name) ?></h1>
															<p><?= $position->info ?></p>
															<p class="mob480 mar-b-2">Se non vedi tutte le impostazioni scorri verso il basso.</p>

															<!-- Toggler button -->
															<div class="toggler">

																<div class="toggler__button">
																	<div class="line"></div>
																	<div class="circle"></div>
																</div>

																<p class="off-text">Disabilitato</p>
																<p class="on-text">Abilitato</p>

															</div>

															<?php if (!$design->custom) { ?>
																<!-- Mockup management -->
																<div class="item-mockup-field mar-t-2 grid gap1">

																	<!-- Size -->
																	<div class="">
																		<p class="lbl mar-b-05">Dimensione:</p>

																		<div class="mockup-inp inp grid">
																			<input type="range" class="mockup-size wid-100 <?= $enabled ? '' : 'disabled' ?>" name="mockup-size" value="<?= $sX ?>" min="25" max="100" step="1" data-land="<?= $orientation ?>" <?= $enabled ? '' : 'disabled' ?>>
																			<p class="txt-med txt-rig padr1"><?= $sX ?> %</p>
																		</div>
																	</div>

																	<!-- Vertical position -->
																	<div class="">
																		<p class="lbl mar-b-05">Posizione verticale:</p>

																		<div class="mockup-inp inp grid">
																			<input type="range" class="mockup-y <?= $enabled ? '' : 'disabled' ?>" name="mockup-y" value="<?= $pY ?>" min="0" max="100" step="1" <?= $enabled ? '' : 'disabled' ?>>
																			<p class="txt-med txt-rig padr1"><?= $pY ?> %</p>
																		</div>
																	</div>

																	<!-- Horizontal position -->
																	<div class="">
																		<p class="lbl mar-b-05">Posizione orizzontale:</p>

																		<div class="mockup-inp inp grid">
																			<input type="range" class="mockup-x <?= $enabled ? '' : 'disabled' ?>" name="mockup-x" value="<?= $pX ?>" min="0" max="100" step="1" <?= $enabled ? '' : 'disabled' ?>>
																			<p class="txt-med txt-rig padr1"><?= $pX ?> %</p>
																		</div>
																	</div>
																</div>

															<?php } ?>
														</div>

														<?php if (!$design->custom) { ?>
															<div class="btn-box text-center mar-t-2">
																<button type="submit" name="update-position" class="pbtn">Applica</button>
															</div>
														<?php } ?>

													</form>

												</section>

												<!-- Color tab -->
												<?php } else {

												$productTypeMockup = ProductTypeMockup::firstByTypeId($product->type_id, $product->position_id);

												$productMockup = ProductMockup::where('product_id', $productId)
													->where('position_id', $product->position_id)
													->first();

												if ($productTypeMockup || $productMockup) {
													$pX = $productMockup->pos_x ?? $productTypeMockup->pos_x_def;
													$pY = $productMockup->pos_y ?? $productTypeMockup->pos_y_def;
													$sX = $sY = $productMockup->size ?? $productTypeMockup->siz_x_def;

													$sX_max = $productTypeMockup->siz_x_max;
													$sY_max = $productTypeMockup->siz_y_max;
													$pX_min = $productTypeMockup->pos_x_min;
													$pY_min = $productTypeMockup->pos_y_min;

													$orientation = $product->design->ratio > $sX_max / $sY_max;

													// $clothingImagePosition = $position->base_of ?: $position->id;
													$clothing = $type->image($product->color_id, $product->position_id);
												?>

													<section class="section<?= $key ?> section colors <?= $key == 1 ? 'active' : '' ?>" data-image-position="<?= $product->type->basicPositionId ?>">

														<!-- Preview image -->
														<div class="image-panel">

															<div class="image mar-h-auto relative">

																<?php if ($design->custom) { ?>

																	<!-- If it's a custom static image -->
																	<div class="bgr" <?= temp_bg($product->image) ?>></div>

																<?php } else { ?>

																	<!-- If it's a normal image -->
																	<div class="background bgr" <?= temp_bg('product_types/mockups/bgr/grey.png') ?>></div>

																	<div class="clothing bgr" <?= temp_bg($clothing) ?> data-type-id="<?= $product->type_id ?>"></div>

																	<div class="design-box relative" style="
											width: <?= $sX_max ?>%;
											height: <?= $sY_max ?>%;
											top: <?= $pY_min ?>%;
											left: <?= $pX_min ?>%;">

																		<div class="design light <?= $product->color->dark ? '' : 'active' ?>" style="
												background-image: url(<?= public_asset($designLight) ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>">
																		</div>

																		<div class="design dark <?= $product->color->dark ? 'active' : '' ?>" style="
												background-image: url(<?= public_asset($designDark) ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>">
																		</div>

																	</div>

																	<?php if (file_exists(SERVER_ROOT . $file = str_replace('.', '-det.', $clothing))) { ?>

																		<div class="details bgr" <?= temp_bg($file) ?>></div>

																<?php }
																} ?>

															</div>

														</div>

														<form method="POST" class="data">

															<input type="hidden" name="product_id" value="<?= $productId ?>">

															<div class="item-colors-field colors-pan">

																<h3 class="txt-15rem sec-title font-semibold mar-b-1 brb2 br-c2">Seleziona i colori disponibili</h3>
																<p class="">Qui puoi selezionare i vari colori disponibili per questo capo e il colore principale, che verrà mostrato in tutte le anteprime.</p>

																<div class="colors-pan mar-t-2">

																	<!-- Available colors -->
																	<p class="data-title inlb">Colori attivi</p><?= info_box("È possibile comprare la tua grafica stampata su capi di questi colori. Attiva quelli che ti piacciono e disattiva quelli che non vuoi che siano disponibili per l'acquisto.") ?>

																	<div class="colorsA mar-b-2">

																		<div class="inp-box color-grid">
																			<?php

																			$colors = $type->colors->merge($product->colors)
																				->unique()
																				->sortBy('hidden')
																				->sortBy('preorder')
																				->sortBy('reorder');

																			foreach ($colors as $color) {
																				$checked = $product->colors->contains('id', $color->id);
																			?>

																				<label class="color-box">

																					<input type="checkbox" class="cbx hidden colorA-<?= $color->tag ?>" name="colorA[]" value="<?= $color->tag ?>" data-color="<?= $color->tag ?>" <?= $checked ? 'checked' : '' ?>>

																					<div class="color-icon" style="background-color: #<?= $color->color ?>;"></div>

																				</label>

																			<?php
																			}
																			?>

																		</div>
																	</div>

																	<!-- Default color -->
																	<p class="data-title inlb">Colore predefinito</p><?= info_box("Qui puoi selezionare il colore del capo che sarà mostrato come prima scelta in tutte le anteprime.") ?>

																	<div class="colorsD">

																		<div class="inp-box color-grid">

																			<?php

																			$colors = $type->colors->merge($product->colors)
																				->unique()
																				->sortBy('hidden')
																				->sortBy('preorder')
																				->sortBy('reorder');

																			foreach ($colors as $color) {
																				$checked = $product->colors->contains('id', $color->id);
																			?>

																				<label class="inlb color-box active">

																					<input type="checkbox" class="cbx hidden colorD-<?= $color->tag ?>" name="colorD" value="<?= $color->id ?>" data-color="<?= $color->tag ?>" data-color-id="<?= $color->id ?>" data-dark="<?= $color->dark ? 1 : 0 ?>" <?= $checked ? 'checked' : '' ?>>

																					<div class="color-icon" for="item-colorD-<?= $color->tag ?>" style="background-color: #<?= $color->hex ?>;"></div>
																				</label>

																			<?php } ?>

																		</div>
																	</div>
																</div>

																<div class="btn-box text-center mar-t-2">
																	<button type="submit" name="update-colors" class="pbtn">Applica</button>
																</div>

															</div>

														</form>

													</section>
										<?php }
											}
										} ?>

									</div>

								</div>

							</div>

						</div>

					</div>

				<?php } ?>
			</div>

		</div>

		<form class="mar-t-4" method="POST">

			<button type="submit" name="delete" id="delete" class="btn btn--secondary button--bad button--auto-width place-center" onclick="return confirm('Sei sicuro di voler eliminare definitivamente la grafica e tutti i prodotti associati?')">
				<?= embed_icon('delete') ?>
				Elimina la grafica
			</button>

		</form>

	</section>
</div>

{% end main %}