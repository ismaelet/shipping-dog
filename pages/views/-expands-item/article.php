<?php

use App\View\Components\Interactions\LikeButton;
use Ecommerce\Product\Color;
use Ecommerce\Product\Gender;
use Ecommerce\Product\Position;
use Ecommerce\Product\Size;
use Ecommerce\Product\Type;

?>

<div class="main-wrap pad-t-3 item-type--<?= $product->type->slug ?>" id="item" data-id="<?= $product->id ?>" data-hroot="<?= CLIENT_ROOT ?>">

	<!-- Warning -->
	<?php if ($warning) { ?>

		<p id="news" class="mar-b-1 <?= $warning['color'] ?>"><?= $warning['text'] ?></p>

	<?php } ?>

	<form method="POST" class="content grid gap3 al-top">

		<div id="left-panel" class="mar-h-auto">

			<!-- Preview image -->
			<section id="img-sec">

				<div id="img-sqr" class="mar-h-auto">

					<div id="item-img-box">

						<!-- Image -->
						<img class="img-zoom img-fill js-main-image" id="item-img" src="<?= $product->clientFullImage ?>" alt="">

						<!-- Logos on the image -->
						<?php if ($product->hasLogos && $product->logoGroup->preview) { ?>

							<img class="logo-field__preview-logo js-logo-image" alt="">

						<?php } ?>

						<!-- Loading icon on image change -->
						<div id="img-load" class="bgr">
							<div id="load-ico"></div>
						</div>

						<!-- Zoom icon on mobile -->
						<div id="img-zoom-icon" class="img-zoom icon-box clickable">
							<?= icon_embed('search') ?>
						</div>

						<!-- Love button -->
						<?= LikeButton::render(['designId' => $product->designId]) ?>

					</div>

				</div>

				<!-- Zoom on mobile -->
				<div id="img-zoom-bgr">
					<div id="img-zoom-box">
						<img class="js-main-image" id="img-zoom" src="<?= $product->clientFullImage ?>">
					</div>
				</div>

			</section>

			<!-- More products with the same design -->
			<?php if (!$product->isCustom) { ?>

				<section id="type-sec">
					<!-- Types -->
					<div id="type-pan">
						<div id="item-type-titles" class="flex gap1 pad-t-2 mar-b-1">

							<?php if ($relatedGarmentCount) { ?>

								<h3 class="item-type-title clickable txt-bold inlb marr1 <?php if ($product->type->category == 'c' || !$relatedAccessoryCount) echo 'active' ?>" data-tar="#more-clothing">Abbigliamento</h3>

							<?php } ?>

							<?php if ($relatedAccessoryCount) { ?>

								<h3 class="item-type-title clickable txt-bold inlb <?php if ($product->type->category == 'm') echo 'active' ?>" data-tar="#more-accessories">Accessori</h3>

							<?php } ?>

						</div>

						<!-- More clothing -->
						<?php if ($relatedGarmentCount) { ?>

							<div id="more-clothing" class="type-slide <?php if ($product->type->category == 'c' || !$relatedAccessoryCount) echo 'active' ?>">

								<div id="type-slideC" data-pos="1" class="carousel mob-fluid">

									<?php foreach ($relatedGarments as $relatedProduct) { ?>

										<div class="carousel-box">
											<a href="<?= $relatedProduct->clientUrl ?>">
												<img src="<?= $relatedProduct->clientImage ?>" class="img-fill">
											</a>
										</div>

									<?php } ?>

								</div>

								<?php if ($relatedGarmentCount > 3) { ?>

									<div class="carousel-arrow left disabled" data-direction="left" data-tar="#type-slideC">
										<div class="icon"></div>
									</div>
									<div class="carousel-arrow right" data-direction="right" data-tar="#type-slideC">
										<div class="icon"></div>
									</div>

								<?php } ?>

							</div>

						<?php } ?>

						<!-- More accessories -->
						<?php if ($relatedAccessoryCount) { ?>

							<div id="more-accessories" class="type-slide <?php if ($product->type->category == 'm') echo 'active' ?>">

								<div id="type-slideM" data-pos="1" class="carousel mob-fluid">

									<?php foreach ($relatedAccessories as $relatedProduct) { ?>

										<div class="carousel-box">
											<a href="<?= $relatedProduct->clientUrl ?>">
												<img src="<?= $relatedProduct->clientImage ?>" class="img-fill">
											</a>
										</div>

									<?php } ?>

								</div>

								<?php if ($relatedAccessoryCount > 3) { ?>

									<div class="carousel-arrow left disabled" data-direction="left" data-tar="#type-slideM">
										<div class="icon"></div>
									</div>

									<div class="carousel-arrow right" data-direction="right" data-tar="#type-slideM">
										<div class="icon"></div>
									</div>

								<?php } ?>

							</div>

						<?php } ?>

					</div>
				</section>

			<?php } ?>

		</div>

		<section id="right-panel" class="grid">
			<div class="">
				<!-- Title -->
				<h1 id="title"><?= $product->name ?></h1>
				<p id="author"><?= $product->type->name ?> by <a class="txt-c1-h" href="<?= CLIENT_ROOT ?>/artist?id=<?= $product->seller->id ?>"><?= $product->seller->name ?></a></p>

				<!-- Price -->
				<div class="price-block">

					<?php if ($product->customerDiscount) { ?>

						<!-- Price when there's a discount -->
						<p class="price price-old heading-like js-full-price"><?= $product->fullPriceString ?></p>
						<p class="price price-promo heading-like js-price"><?= $product->priceString ?></p>

						<div class="flex-group">
							<p class="price-txt">Prezzo scontato<?= $product->isUserOwner ? ' solo per te' : '' ?></p>

							<?php if ($product->isUserOwner) {
								info_box("Questo prezzo speciale è visibile a te, perché sei il proprietario di questo articolo. I clienti vedranno solo il prezzo normale di <span class='js-full-price'>" . $product->fullPriceString . "</span>");
							} ?>
						</div>

					<?php } else { ?>

						<!-- Normal price -->
						<p class="price txt-bold price-def heading-like js-price"><?= $product->priceString ?></p>

					<?php } ?>

				</div>

				<!-- Colors -->
				<?php if (!empty($product->type->availableColorSlugs)) {
					$res = DB::select('ID FROM items_colors ORDER BY reorder ASC, name_it ASC');
				?>

					<section id="color" class="field <?php if (count($product->availableColorSlugs) < 2) echo 'hide' ?>">
						<p class="lbl">Colore</p>
						<div class="grid">

							<?php while ($row = $res->fetch_assoc()) {

								$color = new Color($row['ID']);

								if ((in_array($color->slug, $product->type->availableColorSlugs) || $product->isCustom) && in_array($color->slug, $product->availableColorSlugs)) { ?>

									<input type="checkbox" class="cbx" id="cbx-color-<?= $color->slug ?>" name="cbx-color[]" value="<?= $color->id ?>" data-color="<?= $color->slug ?>" data-id="<?= $color->id ?>" <?php if ($color->slug == $product->color->slug) echo 'checked' ?>>

									<label for="cbx-color-<?= $color->slug ?>" class="color-box">

										<div class="color-lbl <?= $color->bicolor ? 'bicolor' : false ?>" style="background-color: #<?= $color->hex ?>">
											<?php if ($color->bicolor) { ?>
												<div class="bicolor-fill" style="background-color: #<?= $color->hex ?>"></div>
												<div class="bicolor-fill" style="background-color: #<?= $color->secondHex ?>"></div>
											<?php } ?>
										</div>

									</label>

								<?php } ?>

							<?php } ?>

						</div>

					</section>

				<?php } ?>

				<!-- Size -->
				<?php if (!empty($product->type->sizes)) {

					$new = true;

					$size_table = DB::exists('items_types_measures WHERE typeId=?', [$product->type->id]);

					$res = DB::select('ID FROM items_sizes');
				?>

					<section id="size" class="field">
						<p class="lbl">Taglia

							<?php if (!$product->isCustom && $size_table) { ?>
								<span class="lbox-open clickable info txt-c1-h" data-tar="#size-lbox">info taglie</span>
							<?php } ?>

						</p>

						<div class="grid">

							<?php while ($row = $res->fetch_assoc()) {

								$sizeM = new Size($row['ID']);

								if (in_array($sizeM->slug, $product->type->sizes)) { ?>

									<div class="size_box">
										<input type="hidden" value="<?= $sizeM->price ?>">
										<input type="checkbox" class="cbx" id="cbx-type_<?= $sizeM->slug ?>" name="cbx-size[]" value="<?= $sizeM->id ?>" <?php if ($new) {
																																								echo 'checked';
																																								$new = false;
																																							} ?>>

										<label class="size_lbl" for="cbx-type_<?= $sizeM->slug ?>">
											<h1 class="size_txt txt-upp"><?= $sizeM->name ?></h1>
										</label>
									</div>
							<?php }
							} ?>
						</div>

						<!-- Size table -->
						<?php include_once('measure-table.php') ?>

					</section>

				<?php } ?>

				<!-- Gender -->
				<?php if ($product->type->genderId) {
					$new = true;

					$genders = [];
					$genders[] = $product->type->genderId;
					if ($product->type->sibling) $genders[] = (new Type($product->type->sibling))->genderId;

				?>

					<section id="gender" class="field">

						<div class="">
							<p class="lbl">Modello</p>

							<?php if ($product->type->sibling && $product->type->unisex) info_box("Qui puoi scegliere se vedere l'anteprima del prodotto su un modello maschile o femminile, ma ha scopo puramente illustrativo, in quanto tutte le taglie per questo articolo sono unisex.") ?>
						</div>

						<div class="grid">

							<?php foreach ($genders as $key => $genderId) {
								$gender = new Gender($genderId);

								if ($key) {
									$relatedProduct = new Ecommerce\Product(DB::find('ID FROM items WHERE desID = ? AND typeID = ?', [$product->design->id, $product->type->sibling]));
								}

							?>

								<div class="gend_box">
									<input type="checkbox" class="cbx" id="cbx-gend_<?= $gender->slug ?>" name="cbx-gender[]" value="<?= $gender->id ?>" <?php if (!$key) echo 'checked' ?>>

									<a <?php if ($key) echo 'href="' . $relatedProduct->clientUrl . '"' ?> class="gend_lbl">
										<h1 class="gend_txt"><?= $gender->name ?></h1>
									</a>
								</div>

							<?php } ?>

						</div>

					</section>

				<?php } ?>

				<!-- Position -->
				<div id="position" class="field <?= count($product->availablePositionIds) < 2 || $product->isCustom ? 'hidden' : '' ?>">

					<p class="lbl">Posizione della stampa</p>

					<div class="data-area">

						<?php foreach ($product->availablePositionIds as $key => $positionId) {
							$position = new Position($positionId);
						?>

							<label class="data-selector data-selector--position">
								<input type="checkbox" class="js-change-position" name="cbx-pos[]" value="<?= $position->id ?>" <?php if (!$key) echo 'checked' ?>>
								<p class="data-selector__label"><?= $position->name ?></p>
							</label>

						<?php } ?>

					</div>
				</div>

				<!-- Logos -->
				<?php if ($product->hasLogos) { ?>

					<div class="logo-field field">
						<p class="lbl"><?= $product->logoGroup->name ?></p>

						<div class="grid-flow" data-spacing="400">
							<div class="logo-field__list">

								<!-- Checkbox for no logo -->
								<?php if (!$product->logoGroup->forced) { ?>

									<label class="logo-field__item">
										<input type="radio" class="js-logo" name="logo-id" value="0" checked>
										<?= icon_embed('close') ?>
									</label>

								<?php } ?>

								<!--checkboxs for extras-->
								<?php foreach ($product->logos as $logoKey => $logo) { ?>

									<label class="logo-field__item" title="<?= $logo->name ?>">
										<input type="radio" class="js-logo" name="logo-id" value="<?= $logo->id ?>" <?= $product->logoGroup->forced && !$logoKey ? 'checked' : null ?>>
										<img class="img-fill" src="<?= $logo->clientImage ?>" alt="">
									</label>

								<?php } ?>

							</div>

							<div class="logo-field__preview js-logo-image-background" class="<?= $product->logoGroup->forced ? 'active' : null ?>">
								<div class="logo-field__preview-wrapper grid-flow stack-content">
									<img class="logo-field__preview-image js-front-image" src="<?= $product->clientFullImage ?>">
									<img class="logo-field__preview-logo js-logo-image" src="" alt="">
								</div>
							</div>
						</div>
					</div>

				<?php } ?>

				<!-- Pencilcase back -->
				<?php if (false && $product->type->slug == 'cas') { ?>

					<div class="field">
						<p class="lbl">Retro</p>

						<div>
							<input type="checkbox" id="cbx-back_0" class="cbx" name="cbx-back[]" value="0" checked>
							<label class="back_lbl" id="cbx-back_0" for="cbx-back_0" style="background-image: url(/uploads/logos/0.png);"></label>

							<input type="checkbox" id="cbx-back_1" class="cbx" name="cbx-back[]" value="1">
							<label class="back_lbl" for="cbx-back_1" style="background-image: url(/app/assets/icons/correct/apply1_wht.png)"></label>
						</div>

						<div id="back_img_cont">
							<div class="bgr" style="background-image: url(/app/assets/assets/mockups/bgr/gry.png);"></div>
							<div id="back_img" class="bgr" style="background-image: url(/app/assets/assets/mockups/cas/wht_m.png);"></div>
						</div>
					</div>

				<?php } ?>

				<!-- Quantity -->
				<div id="qty_cont" class="field hidden">
					<p class="lbl">Quantità</p>

					<input type="text" id="quantity" name="quantity" value="1">
					<div id="qty_box">
						<h1 id="qty_pls" class="qty_edit on">+</h1>
						<h1 id="qty_min" class="qty_edit off">-</h1>
					</div>
				</div>

				<!-- Add to cart -->
				<div class="mar-t-4">

					<?php if (($product->statusId && !$product->deleted) || $owner) { ?>

						<button type="submit" id="submit" name="submit" class="add-to-cart button button--full-width">
							<?= icon_embed('cart') ?>
							Aggiungi al carrello
						</button>

					<?php } ?>

					<?php if (!$product->isCustom && $product->type->blankId && $product->type->blankId != $product->id) { ?>

						<p class="mar-t-1">Vuoi acquistare il prodotto senza grafica? <a class="txt-bold txt-c-title" href="<?= $product->type->blankProduct->clientUrl ?>">Clicca qui</a>.</p>

					<?php } ?>

				</div>
			</div>
		</section>
	</form>
</div>