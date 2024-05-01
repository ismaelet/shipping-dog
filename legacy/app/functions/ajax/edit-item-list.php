<?php

use App\Models\Ecommerce\Product as EcommerceProduct;
use App\Models\Ecommerce\Product\ProductMockup;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Product\ProductTypeMockup;
use Legacy\Classes\Ecommerce\Design;
use Legacy\Classes\Ecommerce\Product;
use Legacy\Classes\Ecommerce\Product\Color;
use Legacy\Classes\Ecommerce\Product\Gender;
use Legacy\Classes\Ecommerce\Product\Position;
use Legacy\Classes\Ecommerce\Product\Type;

$oldDesign_id = $_GET['design_id'];
$oldDesign = new Design($oldDesign_id);

if (!$oldDesign->custom && auth()->id() != 1) {
	$oldTypes = \Legacy\System\Classes\LegacyDB::select('id FROM product_types WHERE hidden = 0 ORDER BY category ASC, id ASC');
} else {
	$oldTypes = \Legacy\System\Classes\LegacyDB::select('id FROM product_types ORDER BY hidden ASC, category ASC, id ASC');
}

while ($oldTypeRow = $oldTypes->fetch_assoc()) {
	$oldType = new Type($oldTypeRow['id']);
	$type = ProductType::find($oldType->id);
	$gender = new Gender($oldType->genderId);

	$oldProductId = \Legacy\System\Classes\LegacyDB::find('id FROM items WHERE design_id = ? AND type_id = ?', [$oldDesign->id, $oldType->id]);

	$oldProduct = new Product($oldProductId);
	$product = EcommerceProduct::find($oldProduct->id);

	$active = $oldProduct->statusId;

	if ($oldProduct->statusId) $oldProductImage = $oldProduct->image;
	else if (public_storage($oldProduct->image) && !$oldProduct->isCustom) $oldProductImage = $oldProduct->image;
	else $oldProductImage = $oldProduct->type->blankImage;

	// tabls
	$tabs = [];

	if (count($oldType->colors) > 1) $tabs[] = ['Colori', 'colors'];

	foreach ($oldType->availablePositionIds as $position_id) {
		$position = new Position($position_id);
		if ($position->editable) $tabs[] = [$position->name, 'position', $position_id];
	}

	use_natural_keys($tabs);

	$oldDesignLight = '/designs/' . $oldDesign->id . '.png';
	$oldDesignDark = file_exists(public_storage($file = '/designs/' . $oldDesign->id . '_dark.png')) ? $file : $oldDesignLight;
?>

	<div id="item-<?= $oldType->id ?>" class="item <?= $active ? 'active' : '' ?>">

		<div class="item-img item-panel-open pos-rel">
			<img src="<?= $oldProductImage ?>" alt="" class="img-fill">
			<div class="item-over bgr"></div>
		</div>

		<h3 class="type-name txt-1rem txt-med txt-cen mar-t-05"><?= $oldType->name;
																if ($gender->id) echo ' - ' . $gender->name ?></h3>

		<form method="POST" class="img-buttons grid mar-t-05">

			<input type="hidden" name="type_id" value="<?= $oldType->id ?>">

			<?php if ($oldProduct->status) { ?>

				<button type="submit" name="off" class="btn off-btn switch-btn ">
					<?= embed_icon('actions/add-bgr') ?>
					Attivo
				</button>

				<p class="sbtn edit-btn switch-btn item-panel-open">
					<?= embed_icon('settings') ?>
					Modifica
				</p>

			<?php } else { ?>

				<button type="submit" name="on" class="btn btn--secondary on-btn switch-btn">
					<?= embed_icon('actions/add-bgr') ?>
					Disattivato
				</button>

			<?php } ?>

		</form>

		<div class="item-panel" data-id=<?= $oldProduct->id ?>>

			<div class="box pos-rel">

				<div class="tabs">

					<?php foreach ($tabs as $key => $tab) { ?>
						<h3 class="tab txt-upp <?= $key == 1 ? 'active' : '' ?>" data-id="<?= $key ?>"><?= $tab[0] ?></h3>
					<?php } ?>

				</div>

				<div class="wrapper">

					<div class="close-icon item-panel-close icon clickable text-neutral-700"><?= embed_icon('close') ?></div>

					<div class="content">

						<!-- Position tabs -->
						<?php foreach ($tabs as $key => $tab) {
							if ($tab[1] == 'position') {
								$position = new Position($tab[2]);

								$enabled = in_array($position->id, $oldProduct->availablePositionIds);

								$productTypeMockup = ProductTypeMockup::firstByTypeId($product->type_id, $position->id);
								$productMockup = ProductMockup::where('product_id', $product->id)
									->where('position_id', $position->id)
									->first();

								$pX = $productMockup->pos_x ?? $productTypeMockup->pos_x_def;
								$pY = $productMockup->pos_y ?? $productTypeMockup->pos_y_def;
								$sX = $sY = $productMockup->size ?? $productTypeMockup->siz_x_def;

								$sX_max = $productTypeMockup->siz_x_max;
								$sY_max = $productTypeMockup->siz_y_max;
								$pX_min = $productTypeMockup->pos_x_min;
								$pY_min = $productTypeMockup->pos_y_min;

								$orientation = $oldProduct->design->ratio > $sX_max / $sY_max;

								$clothing = $type->image($oldProduct->color_id, $position->imagePositionId);
						?>

								<section class="section<?= $key ?> section positions <?= $key == 1 ? 'active' : '' ?> <?= $enabled ? '' : 'disabled' ?>" data-enabled="<?= $enabled ? 1 : 0 ?>" data-image-position="<?= $position->imagePositionId ?>">

									<input type="hidden" class="js-enabled" data-position="<?= $position->id ?>" data-enabled="<?= $enabled ? 1 : 0 ?>">

									<!-- Preview image -->
									<div class="image-panel">

										<div class="image mar-h-auto pos-rel">

											<?php if ($oldDesign->custom) { ?>

												<!-- If it's a custom static image -->
												<div class="bgr" <?= temp_bg($oldProduct->image) ?>></div>

											<?php } else { ?>

												<!-- If it's a normal image -->
												<div class="background bgr" <?= temp_bg('product_types/mockups/bgr/grey.png') ?>></div>

												<div class="clothing bgr" <?= temp_bg($clothing) ?> data-type-id="<?= $oldProduct->type_id ?>"></div>

												<div class="design-box" style="
											width: <?= $sX_max ?>%;
											height: <?= $sY_max ?>%;
											top: <?= $pY_min ?>%;
											left: <?= $pX_min ?>%;">

													<div class="design light <?= $oldProduct->color->dark ? '' : 'active' ?>" style="
												background-image: url(<?= public_asset($oldDesignLight) ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>">
													</div>

													<div class="design dark <?= $oldProduct->color->dark ? 'active' : '' ?>" style="
												background-image: url(<?= public_asset($oldDesignDark) ?>);
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

										<input type="hidden" name="product_id" value="<?= $oldProduct->id ?>">
										<input type="hidden" name="position_id" value="<?= $position->id ?>">

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

											<?php if (!$oldDesign->custom) { ?>
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

										<?php if (!$oldDesign->custom) { ?>
											<div class="btn-box txt-cen mar-t-2">
												<button type="submit" name="update-position" class="pbtn">Applica</button>
											</div>
										<?php } ?>

									</form>

								</section>

								<!-- Color tab -->
								<?php } else {

								$productTypeMockup = ProductTypeMockup::firstByTypeId($product->type_id, $product->defaultPositionId());

								$productMockup = ProductMockup::where('product_id', $product->id)
									->where('position_id', $product->defaultPositionId())
									->first();

								if ($productTypeMockup || $productMockup) {
									$pX = $productMockup->pos_x ?? $productTypeMockup->pos_x_def;
									$pY = $productMockup->pos_y ?? $productTypeMockup->pos_y_def;
									$sX = $sY = $productMockup->size ?? $productTypeMockup->siz_x_def;

									$sX_max = $productTypeMockup->siz_x_max;
									$sY_max = $productTypeMockup->siz_y_max;
									$pX_min = $productTypeMockup->pos_x_min;
									$pY_min = $productTypeMockup->pos_y_min;

									$orientation = $oldProduct->design->ratio > $sX_max / $sY_max;

									// $clothingImagePosition = $position->base_of ?: $position->id;
									$clothing = $type->image($oldProduct->color_id, $oldProduct->position_id);
								?>

									<section class="section<?= $key ?> section colors <?= $key == 1 ? 'active' : '' ?>" data-image-position="<?= $oldProduct->type->basicPositionId ?>">

										<!-- Preview image -->
										<div class="image-panel">

											<div class="image mar-h-auto pos-rel">

												<?php if ($oldDesign->custom) { ?>

													<!-- If it's a custom static image -->
													<div class="bgr" <?= temp_bg($oldProduct->image) ?>></div>

												<?php } else { ?>

													<!-- If it's a normal image -->
													<div class="background bgr" <?= temp_bg('product_types/mockups/bgr/grey.png') ?>></div>

													<div class="clothing bgr" <?= temp_bg($clothing) ?> data-type-id="<?= $oldProduct->type_id ?>"></div>

													<div class="design-box pos-rel" style="
											width: <?= $sX_max ?>%;
											height: <?= $sY_max ?>%;
											top: <?= $pY_min ?>%;
											left: <?= $pX_min ?>%;">

														<div class="design light <?= $oldProduct->color->dark ? '' : 'active' ?>" style="
												background-image: url(<?= public_asset($oldDesignLight) ?>);
												background-position: <?= $pX . '% ' . $pY . '%' ?>;
												background-size: <?= $orientation ? $sX . '% auto' : 'auto ' . $sX . '%' ?>">
														</div>

														<div class="design dark <?= $oldProduct->color->dark ? 'active' : '' ?>" style="
												background-image: url(<?= public_asset($oldDesignDark) ?>);
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

											<input type="hidden" name="product_id" value="<?= $oldProduct->id ?>">

											<div class="item-colors-field colors-pan">

												<h3 class="txt-15rem sec-title txt-bold mar-b-1 brb2 br-c2">Seleziona i colori disponibili</h3>
												<p class="">Qui puoi selezionare i vari colori disponibili per questo capo e il colore principale, che verrà mostrato in tutte le anteprime.</p>

												<div class="colors-pan mar-t-2">

													<!-- Available colors -->
													<p class="data-title inlb">Colori attivi</p><?= info_box("È possibile comprare la tua grafica stampata su capi di questi colori. Attiva quelli che ti piacciono e disattiva quelli che non vuoi che siano disponibili per l'acquisto.") ?>

													<div class="colorsA mar-b-2">

														<div class="inp-box color-grid">
															<?php

															$res_colorA = \Legacy\System\Classes\LegacyDB::select('id FROM items_colors ORDER BY hidden ASC, preorder ASC, reorder ASC, name ASC');

															while ($row = $res_colorA->fetch_assoc()) {

																$color = new Color($row['id']);

																if (in_array($color->slug, $oldType->availableColorSlugs)) { ?>

																	<label class="color-box">

																		<input type="checkbox" class="cbx hidden colorA-<?= $color->slug ?>" name="colorA[]" value="<?= $color->slug ?>" data-color="<?= $color->slug ?>" <?php if (\Legacy\System\Classes\LegacyDB::exists("items WHERE id = ? AND colors LIKE CONCAT('%',?,'%')", [$oldProduct->id, $color->slug])) echo 'checked' ?>>

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

															$res_colorD = \Legacy\System\Classes\LegacyDB::select('id FROM items_colors ORDER BY id ASC');

															while ($row = $res_colorD->fetch_assoc()) {

																$color = new Color($row['id']);
															?>

																<label class="inlb color-box <?php if (\Legacy\System\Classes\LegacyDB::exists("items WHERE id = ? AND colors LIKE CONCAT('%',?,'%')", [$oldProduct->id, $color->slug])) echo 'active' ?>">

																	<input type="checkbox" class="cbx hidden colorD-<?= $color->slug ?>" name="colorD" value="<?= $color->id ?>" data-color="<?= $color->slug ?>" data-color-id="<?= $color->id ?>" data-dark="<?= $color->dark ? 1 : 0 ?>" <?php if ($color->id == \Legacy\System\Classes\LegacyDB::find('color_id FROM items WHERE id = ?', [$oldProduct->id])) echo 'checked' ?>>

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