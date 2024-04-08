<?php

use App\Models\Ecommerce\Product\ProductType;
use Ecommerce\Design;
use Ecommerce\Product;
use Ecommerce\Product\Color;
use Ecommerce\Product\Gender;
use Ecommerce\Product\Position;
use Ecommerce\Product\Type;

$designId = $_GET['designId'];
$design = new Design($designId);

if (!$design->custom && auth()->id() != 1) {
	$oldTypes = DBOld::select('ID FROM items_types WHERE hidden = 0 ORDER BY category ASC, ID ASC');
} else {
	$oldTypes = DBOld::select('ID FROM items_types ORDER BY hidden ASC, category ASC, ID ASC');
}

while ($oldTypeRow = $oldTypes->fetch_assoc()) {
	$oldType = new Type($oldTypeRow['ID']);
	$type = ProductType::find($oldType->id);
	$gender = new Gender($oldType->genderId);

	$productId = DBOld::find('ID FROM items WHERE desID = ? AND typeID = ?', [$design->id, $oldType->id]);

	$product = new Product($productId);

	$active = $product->statusId;

	if ($product->statusId) $productImage = $product->image;
	else if (public_storage_path($product->image) && !$product->isCustom) $productImage = $product->image;
	else $productImage = $product->type->blankImage;

	// tabls
	$tabs = [];

	if (count($oldType->colors) > 1) $tabs[] = ['Colori', 'colors'];

	foreach ($oldType->availablePositionIds as $positionId) {
		$position = new Position($positionId);
		if ($position->editable) $tabs[] = [$position->name, 'position', $positionId];
	}

	use_natural_keys($tabs);

	$designLight = '/designs/' . $design->id . '.png';
	$designDark = file_exists(public_storage_path($file = '/designs/' . $design->id . '_dark.png')) ? $file : $designLight;
?>

	<div id="item-<?= $oldType->id ?>" class="item <?= $active ? 'active' : '' ?>">

		<div class="item-img item-panel-open pos-rel">
			<img src="<?= $productImage ?>" alt="" class="img-fill">
			<div class="item-over bgr"></div>
		</div>

		<h3 class="type-name txt-1rem txt-med txt-cen mar-t-05"><?= $oldType->name;
																if ($gender->id) echo ' - ' . $gender->name ?></h3>

		<form method="POST" class="img-buttons grid mar-t-05">

			<input type="hidden" name="typeID" value="<?= $oldType->id ?>">

			<button type="submit" name="off" class="off-btn switch-btn button">
				<span class="btn-icon bgr-wht" <?= embed_icon('actions/add-bgr') ?>></span>
				<span class="btn-txt">Attivo</span>
			</button>

			<button type="submit" name="on" class="on-btn switch-btn button">
				<span class="btn-icon bgr-5" <?= embed_icon('actions/add-bgr') ?>></span>
				<span class="btn-txt txt-5">Disattivato</span>
			</button>

			<p class="sbtn edit-btn switch-btn item-panel-open">
				<span class="btn-icon bgr-c900" <?= embed_icon('settings') ?>></span>
				<span class="btn-txt">Modifica</span>
			</p>

		</form>

		<div class="item-panel" data-id=<?= $product->id ?>>

			<div class="box pos-rel">

				<div class="tabs">

					<?php foreach ($tabs as $key => $tab) { ?>
						<h3 class="tab txt-upp <?= $key == 1 ? 'active' : '' ?>" data-id="<?= $key ?>"><?= $tab[0] ?></h3>
					<?php } ?>

				</div>

				<div class="wrapper">

					<div class="close-icon item-panel-close icon clickable bgr-c900 bgr-c1-h" <?= embed_icon('actions/close') ?>></div>

					<div class="content">

						<!-- Position tabs -->
						<?php foreach ($tabs as $key => $tab) {
							if ($tab[1] == 'position') {
								$position = new Position($tab[2]);

								$enabled = in_array($position->id, $product->availablePositionIds);

								$base = DBOld::row('* FROM items_mockups WHERE typeID = ? AND positionID = ?', [$product->type->id, $position->id]);
								$mockup = DBOld::row('* FROM items_previews WHERE itemID = ? AND positionID = ?', [$product->id, $position->id]);

								$pX = $mockup['pos_x'] ?? $base['pos_x_def'];
								$pY = $mockup['pos_y'] ?? $base['pos_y_def'];
								$sX = $sY = $mockup['size'] ?? $base['siz_x_def'];

								$sX_max = $base['siz_x_max'];
								$sY_max = $base['siz_y_max'];
								$pX_min = $base['pos_x_min'];
								$pY_min = $base['pos_y_min'];

								$orientation = $product->design->ratio > $sX_max / $sY_max;

								$clothing = $type->image($product->colorId, $position->imagePositionId);
						?>

								<section class="section<?= $key ?> section positions <?= $key == 1 ? 'active' : '' ?> <?= $enabled ? '' : 'disabled' ?>" data-enabled="<?= $enabled ? 1 : 0 ?>" data-image-position="<?= $position->imagePositionId ?>">

									<input type="hidden" class="js-enabled" data-position="<?= $position->id ?>" data-enabled="<?= $enabled ? 1 : 0 ?>">

									<!-- Preview image -->
									<div class="image-panel">

										<div class="image mar-h-auto pos-rel">

											<?php if ($design->custom) { ?>

												<!-- If it's a custom static image -->
												<div class="bgr" <?= temp_bg($product->image) ?>></div>

											<?php } else { ?>

												<!-- If it's a normal image -->
												<div class="background bgr" <?= temp_bg('product_types/mockups/bgr/grey.png') ?>></div>

												<div class="clothing bgr" <?= temp_bg($clothing) ?> data-type-id="<?= $product->typeId ?>"></div>

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

												<?php if (file_exists(public_storage_path($file = str_replace('.', '-det.', $clothing)))) { ?>
													<div class="details bgr" <?= temp_bg($file) ?>></div>
											<?php }
											} ?>

										</div>

									</div>

									<form method="POST" class="data">

										<input type="hidden" name="itemID" value="<?= $product->id ?>">
										<input type="hidden" name="positionID" value="<?= $position->id ?>">

										<div>
											<h1 class="txt-15rem sec-title txt-bold mar-b-1 brb2 br-c2">Stampa in posizione <?= strtolower($position->name) ?></h1>
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
											<div class="btn-box txt-cen mar-t-2">
												<button type="submit" name="update-position" class="pbtn">Applica</button>
											</div>
										<?php } ?>

									</form>

								</section>

								<!-- Color tab -->
								<?php } else {
								$base = DBOld::row('* FROM items_mockups WHERE typeID = ? AND positionID = ?', [$product->type->id, $product->positionId]);
								$mockup = DBOld::row('* FROM items_previews WHERE itemID = ? AND positionID = ?', [$product->id, $product->positionId]);

								if ($base || $mockup) {
									$pX = $mockup['pos_x'] ?? $base['pos_x_def'];
									$pY = $mockup['pos_y'] ?? $base['pos_y_def'];
									$sX = $sY = $mockup['size'] ?? $base['siz_x_def'];

									$sX_max = $base['siz_x_max'];
									$sY_max = $base['siz_y_max'];
									$pX_min = $base['pos_x_min'];
									$pY_min = $base['pos_y_min'];

									$orientation = $product->design->ratio > $sX_max / $sY_max;

									// $clothingImagePosition = $position->base_of ?: $position->id;
									$clothing = $type->image($product->colorId, $product->positionId);
								?>

									<section class="section<?= $key ?> section colors <?= $key == 1 ? 'active' : '' ?>" data-image-position="<?= $product->type->basicPositionId ?>">

										<!-- Preview image -->
										<div class="image-panel">

											<div class="image mar-h-auto pos-rel">

												<?php if ($design->custom) { ?>

													<!-- If it's a custom static image -->
													<div class="bgr" <?= temp_bg($product->image) ?>></div>

												<?php } else { ?>

													<!-- If it's a normal image -->
													<div class="background bgr" <?= temp_bg('product_types/mockups/bgr/grey.png') ?>></div>

													<div class="clothing bgr" <?= temp_bg($clothing) ?> data-type-id="<?= $product->typeId ?>"></div>

													<div class="design-box pos-rel" style="
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

											<input type="hidden" name="itemID" value="<?= $product->id ?>">

											<div class="item-colors-field colors-pan">

												<h3 class="txt-15rem sec-title txt-bold mar-b-1 brb2 br-c2">Seleziona i colori disponibili</h3>
												<p class="">Qui puoi selezionare i vari colori disponibili per questo capo e il colore principale, che verrà mostrato in tutte le anteprime.</p>

												<div class="colors-pan mar-t-2">

													<!-- Available colors -->
													<p class="data-title inlb">Colori attivi</p><?= info_box("È possibile comprare la tua grafica stampata su capi di questi colori. Attiva quelli che ti piacciono e disattiva quelli che non vuoi che siano disponibili per l'acquisto.") ?>

													<div class="colorsA mar-b-2">

														<div class="inp-box color-grid">
															<?php

															$res_colorA = DBOld::select('ID FROM items_colors ORDER BY hidden ASC, preorder ASC, reorder ASC, name_it ASC');

															while ($row = $res_colorA->fetch_assoc()) {

																$color = new Color($row['ID']);

																if (in_array($color->slug, $oldType->availableColorSlugs)) { ?>

																	<label class="color-box">

																		<input type="checkbox" class="cbx hidden colorA-<?= $color->slug ?>" name="colorA[]" value="<?= $color->slug ?>" data-color="<?= $color->slug ?>" <?php if (DBOld::exists("items WHERE ID = ? AND colors LIKE CONCAT('%',?,'%')", [$product->id, $color->slug])) echo 'checked' ?>>

																		<div class="color-icon" style="background-color: #<?= $color->hex ?>;"></div>

																	</label>

															<?php }
															}
															?>

														</div>
													</div>

													<!-- Default color -->
													<p class="data-title inlb">Colore predefinito</p><?= info_box("Qui puoi selezionare il colore del capo che sarà mostrato come prima scelta in tutte le anteprime.") ?>

													<div class="colorsD">

														<div class="inp-box color-grid">

															<?php

															$res_colorD = DBOld::select('ID FROM items_colors ORDER BY ID ASC');

															while ($row = $res_colorD->fetch_assoc()) {

																$color = new Color($row['ID']) ?>

																<label class="inlb color-box <?php if (DBOld::exists("items WHERE ID = ? AND colors LIKE CONCAT('%',?,'%')", [$product->id, $color->slug])) echo 'active' ?>">

																	<input type="checkbox" class="cbx hidden colorD-<?= $color->slug ?>" name="colorD" value="<?= $color->id ?>" data-color="<?= $color->slug ?>" data-color-id="<?= $color->id ?>" data-dark="<?= $color->dark ? 1 : 0 ?>" <?php if ($color->id == DBOld::find('colorID FROM items WHERE ID = ?', [$product->id])) echo 'checked' ?>>

																	<div class="color-icon" for="item-colorD-<?= $color->slug ?>" style="background-color: #<?= $color->hex ?>;"></div>
																</label>

															<?php } ?>

														</div>
													</div>
												</div>

												<div class="btn-box txt-cen mar-t-2">
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